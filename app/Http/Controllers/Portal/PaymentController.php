<?php

namespace App\Http\Controllers\Portal;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Payment;
use App\Services\Paystack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student()->firstOrFail();

        return view('portal.payments', [
            'payments' => $student->payments()->with('fee')->latest()->paginate(20),
        ]);
    }

    /**
     * Create a pending payment for the outstanding balance and send the student to Paystack.
     */
    public function pay(Request $request, Fee $fee, Paystack $paystack)
    {
        $user = $request->user();
        $student = $user->student()->firstOrFail();

        abort_unless($student->feeApplies($fee), 404);

        $balance = $student->balanceFor($fee);

        if ($balance <= 0) {
            return redirect()->route('portal.dashboard')->with('success', 'This fee is already fully paid.');
        }

        $payment = $student->payments()->create([
            'fee_id' => $fee->id,
            'reference' => Payment::newReference(),
            'amount' => $balance,
            'method' => PaymentMethod::Paystack,
            'status' => PaymentStatus::Pending,
        ]);

        try {
            $checkoutUrl = $paystack->initialize(
                email: $user->email,
                amountInKobo: $balance,
                reference: $payment->reference,
                callbackUrl: route('portal.payments.callback'),
                metadata: ['student_id' => $student->id, 'reg_number' => $student->reg_number, 'fee' => $fee->label()],
            );
        } catch (RuntimeException $exception) {
            Log::warning('Paystack initialize failed', ['reference' => $payment->reference, 'error' => $exception->getMessage()]);
            $payment->update(['status' => PaymentStatus::Failed, 'note' => 'Could not start online payment.']);

            return redirect()->route('portal.dashboard')->withErrors(['payment' => 'Online payment is not available right now. Please try again later or pay at the bursary.']);
        }

        return redirect()->away($checkoutUrl);
    }

    /**
     * Paystack sends the student back here after checkout.
     */
    public function callback(Request $request, Paystack $paystack)
    {
        $reference = (string) $request->query('reference', $request->query('trxref', ''));

        $payment = $request->user()->student->payments()->where('reference', $reference)->first();

        if (! $payment) {
            return redirect()->route('portal.dashboard')->withErrors(['payment' => 'We could not find that payment.']);
        }

        if ($payment->status === PaymentStatus::Pending) {
            try {
                $payment->applyGatewayResult($paystack->verify($reference));
            } catch (RuntimeException $exception) {
                Log::warning('Paystack verify failed', ['reference' => $reference, 'error' => $exception->getMessage()]);

                return redirect()->route('portal.payments.index')->withErrors(['payment' => 'We could not confirm your payment yet. If you were debited, it will update automatically shortly.']);
            }
        }

        if ($payment->isSuccessful()) {
            return redirect()->route('portal.payments.receipt', $payment)->with('success', 'Payment successful. Thank you!');
        }

        return redirect()->route('portal.dashboard')->withErrors(['payment' => 'The payment was not completed. You have not been charged for this attempt.']);
    }

    public function receipt(Request $request, Payment $payment)
    {
        abort_unless($payment->student_id === $request->user()->student?->id && $payment->isSuccessful(), 404);

        return view('receipt', ['payment' => $payment->load('student.user', 'student.course', 'fee'), 'backUrl' => route('portal.payments.index')]);
    }
}
