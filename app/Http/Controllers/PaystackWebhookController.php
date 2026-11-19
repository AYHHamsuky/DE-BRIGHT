<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\Paystack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Paystack calls this from its servers when a payment finishes, even if the
 * student closed the browser before returning to the site.
 *
 * Set the webhook URL in the Paystack dashboard to: https://your-site/payments/paystack/webhook
 */
class PaystackWebhookController extends Controller
{
    public function __invoke(Request $request, Paystack $paystack)
    {
        if (! $paystack->hasValidSignature($request->getContent(), $request->header('x-paystack-signature'))) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        if ($request->input('event') !== 'charge.success') {
            return response()->json(['message' => 'Ignored']);
        }

        $payment = Payment::where('reference', (string) $request->input('data.reference'))->first();

        if ($payment) {
            try {
                // Never trust the webhook body for the amount: ask Paystack directly.
                $payment->applyGatewayResult($paystack->verify($payment->reference));
            } catch (RuntimeException $exception) {
                Log::warning('Paystack webhook verify failed', ['reference' => $payment->reference, 'error' => $exception->getMessage()]);

                return response()->json(['message' => 'Retry later'], 503);
            }
        }

        return response()->json(['message' => 'OK']);
    }
}
