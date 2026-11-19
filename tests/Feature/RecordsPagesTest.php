<?php

namespace Tests\Feature;

use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecordsPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_pages_render_with_records(): void
    {
        $payment = Payment::factory()->successful()->create();
        $student = $payment->student;
        $superAdmin = User::factory()->superAdmin()->create();

        foreach ([
            '/admin',
            '/admin/students',
            "/admin/students/{$student->id}",
            "/admin/students/{$student->id}/edit",
            '/admin/students/create',
            '/admin/fees',
            '/admin/fees/create',
            "/admin/fees/{$payment->fee_id}/edit",
            '/admin/payments',
            '/admin/payments/create',
            "/admin/payments/create?student={$student->id}",
            "/admin/payments/{$payment->id}",
            "/admin/payments/{$payment->id}/receipt",
            '/admin/users',
            '/admin/users/create',
            '/admin/account',
        ] as $url) {
            $this->actingAs($superAdmin)->get($url)->assertOk();
        }
    }

    public function test_student_pages_show_balance_and_history(): void
    {
        config(['services.paystack.secret_key' => 'sk_test_secret']);
        $student = Student::factory()->create();
        $fee = Fee::factory()->create(['name' => 'Acceptance fee', 'amount' => 2_500_000]);
        Payment::factory()->successful()->for($student)->for($fee)->create(['amount' => 1_000_000, 'reference' => 'DBC-TEST-REF']);

        $this->actingAs($student->user)->get('/portal')
            ->assertOk()
            ->assertSee('Acceptance fee')
            ->assertSee('Pay ₦15,000.00');

        $this->actingAs($student->user)->get('/portal/payments')->assertOk()->assertSee('DBC-TEST-REF');
        $this->actingAs($student->user)->get('/portal/password')->assertOk();
    }
}
