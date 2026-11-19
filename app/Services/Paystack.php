<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Thin wrapper around the Paystack transaction API.
 *
 * @see https://paystack.com/docs/api/transaction/
 */
class Paystack
{
    public function __construct(
        private readonly ?string $secretKey,
        private readonly string $baseUrl = 'https://api.paystack.co',
    ) {}

    public function isConfigured(): bool
    {
        return filled($this->secretKey);
    }

    /**
     * Start a transaction and return the checkout URL to send the student to.
     *
     * @param  array<string, mixed>  $metadata
     */
    public function initialize(string $email, int $amountInKobo, string $reference, string $callbackUrl, array $metadata = []): string
    {
        $response = $this->client()->post('/transaction/initialize', [
            'email' => $email,
            'amount' => $amountInKobo,
            'currency' => 'NGN',
            'reference' => $reference,
            'callback_url' => $callbackUrl,
            'metadata' => $metadata,
        ]);

        if (! $response->successful() || ! $response->json('status')) {
            throw new RuntimeException('Paystack could not start the payment: '.($response->json('message') ?? 'unknown error'));
        }

        return $response->json('data.authorization_url');
    }

    /**
     * Ask Paystack for the final state of a transaction.
     *
     * @return array<string, mixed> The transaction "data" object.
     */
    public function verify(string $reference): array
    {
        $response = $this->client()->get('/transaction/verify/'.rawurlencode($reference));

        if (! $response->successful() || ! $response->json('status')) {
            throw new RuntimeException('Paystack could not verify the payment: '.($response->json('message') ?? 'unknown error'));
        }

        return $response->json('data');
    }

    /**
     * Check that a webhook really came from Paystack.
     */
    public function hasValidSignature(string $payload, ?string $signature): bool
    {
        if (! $this->isConfigured() || blank($signature)) {
            return false;
        }

        return hash_equals(hash_hmac('sha512', $payload, $this->secretKey), $signature);
    }

    private function client(): PendingRequest
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Online payment is not set up yet. Add PAYSTACK_SECRET_KEY to the .env file.');
        }

        return Http::baseUrl($this->baseUrl)->withToken($this->secretKey)->acceptJson()->timeout(20);
    }
}
