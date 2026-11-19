<?php

namespace Tests\Feature;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Course;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private const SECRET = 'sk_test_secret';

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.paystack.secret_key' => self::SECRET]);
        Http::preventStrayRequests();
    }

    public function test_student_is_sent_to_paystack_for_the_outstanding_balance(): void
    {
        $student = Student::factory()->create();
        $fee = Fee::factory()->create(['amount' => 5_000_000]);
        Payment::factory()->successful()->for($student)->for($fee)->create(['amount' => 2_000_000]);

        Http::fake(['api.paystack.co/transaction/initialize' => Http::response([
            'status' => true,
            'data' => ['authorization_url' => 'https://checkout.paystack.com/abc'],
        ])]);

        $this->actingAs($student->user)->post("/portal/fees/{$fee->id}/pay")
            ->assertRedirect('https://checkout.paystack.com/abc');

        $pending = $student->payments()->where('status', PaymentStatus::Pending)->sole();
        $this->assertSame(3_000_000, $pending->amount);
        Http::assertSent(fn ($request) => $request['amount'] === 3_000_000 && $request['reference'] === $pending->reference);
    }

    public function test_student_cannot_pay_a_fee_for_another_course(): void
    {
        $student = Student::factory()->create(['course_id' => Course::create(['name' => 'MLT', 'level' => 'Diploma', 'duration' => '3 Years'])->id]);
        $otherCourseFee = Fee::factory()->create(['course_id' => Course::create(['name' => 'HIM', 'level' => 'Diploma', 'duration' => '3 Years'])->id]);

        $this->actingAs($student->user)->post("/portal/fees/{$otherCourseFee->id}/pay")->assertNotFound();
    }

    public function test_callback_marks_a_verified_payment_as_paid(): void
    {
        $payment = Payment::factory()->create(['amount' => 5_000_000]);
        $this->fakeVerify($payment->reference, 'success', 5_000_000);

        $this->actingAs($payment->student->user)
            ->get('/portal/payments/callback?reference='.$payment->reference)
            ->assertRedirect("/portal/payments/{$payment->reference}/receipt");

        $this->assertSame(PaymentStatus::Successful, $payment->fresh()->status);
        $this->assertNotNull($payment->fresh()->paid_at);
    }

    public function test_callback_rejects_a_payment_with_the_wrong_amount(): void
    {
        $payment = Payment::factory()->create(['amount' => 5_000_000]);
        $this->fakeVerify($payment->reference, 'success', 100);

        $this->actingAs($payment->student->user)->get('/portal/payments/callback?reference='.$payment->reference);

        $this->assertSame(PaymentStatus::Failed, $payment->fresh()->status);
    }

    public function test_webhook_with_valid_signature_marks_payment_as_paid(): void
    {
        $payment = Payment::factory()->create(['amount' => 5_000_000]);
        $this->fakeVerify($payment->reference, 'success', 5_000_000);
        $body = json_encode(['event' => 'charge.success', 'data' => ['reference' => $payment->reference]]);

        $this->call('POST', '/payments/paystack/webhook', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_PAYSTACK_SIGNATURE' => hash_hmac('sha512', $body, self::SECRET),
        ], $body)->assertOk();

        $this->assertSame(PaymentStatus::Successful, $payment->fresh()->status);
    }

    public function test_webhook_with_bad_signature_is_rejected(): void
    {
        $payment = Payment::factory()->create();
        $body = json_encode(['event' => 'charge.success', 'data' => ['reference' => $payment->reference]]);

        $this->call('POST', '/payments/paystack/webhook', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_PAYSTACK_SIGNATURE' => 'forged',
        ], $body)->assertUnauthorized();

        $this->assertSame(PaymentStatus::Pending, $payment->fresh()->status);
    }

    public function test_student_cannot_see_another_students_receipt(): void
    {
        $payment = Payment::factory()->successful()->create();
        $otherStudent = Student::factory()->create();

        $this->actingAs($otherStudent->user)->get("/portal/payments/{$payment->reference}/receipt")->assertNotFound();
        $this->actingAs($payment->student->user)->get("/portal/payments/{$payment->reference}/receipt")->assertOk()->assertSee($payment->reference);
    }

    public function test_admin_records_a_cash_payment(): void
    {
        $admin = User::factory()->create();
        $student = Student::factory()->create();
        $fee = Fee::factory()->create(['amount' => 5_000_000]);

        $this->actingAs($admin)->post('/admin/payments', [
            'student_id' => $student->id,
            'fee_id' => $fee->id,
            'amount' => '20000',
            'method' => 'cash',
            'paid_at' => now()->subMinute()->format('Y-m-d H:i'),
        ])->assertRedirect();

        $payment = $student->payments()->sole();
        $this->assertSame(2_000_000, $payment->amount);
        $this->assertSame(PaymentMethod::Cash, $payment->method);
        $this->assertTrue($payment->isSuccessful());
        $this->assertTrue($payment->recorder->is($admin));
        $this->assertSame(3_000_000, $student->balanceFor($fee));
    }

    public function test_admin_cannot_record_more_than_the_balance(): void
    {
        $student = Student::factory()->create();
        $fee = Fee::factory()->create(['amount' => 5_000_000]);

        $this->actingAs(User::factory()->create())->post('/admin/payments', [
            'student_id' => $student->id,
            'fee_id' => $fee->id,
            'amount' => '60000',
            'method' => 'cash',
            'paid_at' => now()->subMinute()->format('Y-m-d H:i'),
        ])->assertSessionHasErrors('amount');

        $this->assertSame(0, $student->payments()->count());
    }

    private function fakeVerify(string $reference, string $status, int $amount): void
    {
        Http::fake(["api.paystack.co/transaction/verify/{$reference}" => Http::response([
            'status' => true,
            'data' => ['reference' => $reference, 'status' => $status, 'amount' => $amount, 'currency' => 'NGN', 'paid_at' => now()->toIso8601String()],
        ])]);
    }
}
