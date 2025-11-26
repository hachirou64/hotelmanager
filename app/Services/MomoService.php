<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MomoService
{
    /**
     * Initiate a MOMO payment (STK push or equivalent).
     * This implementation is a small abstraction: if MOMO credentials are missing it simulates a response
     * so you can develop locally. Replace with real provider SDK/http calls and proper error handling.
     *
     * @param string $phone E.164 or local phone depending on provider/sandbox
     * @param float $amount
     * @param string $callbackUrl
     * @param array $metadata
     * @return array ['transaction_id' => string, 'status' => 'pending', 'provider_payload' => mixed]
     */
    public function initiatePayment(string $phone, float $amount, string $callbackUrl, array $metadata = [], string $provider = 'mtn'): array
    {
        $sandbox = config('momo.sandbox', true);

        if ($sandbox) {
            // Simulate transaction id and pending status and simulate STK push to phone
            $transactionId = strtoupper($provider) . '-SIM-' . time() . '-' . rand(1000, 9999);

            // In sandbox we can't actually push to a phone; log payload so dev can simulate.
            \Log::info('MomoService sandbox STK push', [
                'provider' => $provider,
                'phone' => $phone,
                'amount' => $amount,
                'callback' => $callbackUrl,
                'metadata' => $metadata,
                'transaction_id' => $transactionId,
            ]);

            return [
                'transaction_id' => $transactionId,
                'status' => 'pending',
                'provider_payload' => [
                    'simulated' => true,
                    'provider' => $provider,
                    'phone' => $phone,
                    'amount' => $amount,
                    'callback' => $callbackUrl,
                ],
            ];
        }

        // Example REAL flow placeholder (adapt to your provider API):
    $endpoint = config('momo.endpoint');
    $clientId = config('momo.client_id');
    $clientSecret = config('momo.client_secret');

        // Acquire token (provider specific) - pseudo-code
        $tokenResp = Http::withBasicAuth($clientId, $clientSecret)->post($endpoint . '/oauth/token', [
            'grant_type' => 'client_credentials',
        ]);

        if (! $tokenResp->ok()) {
            throw new \Exception('MOMO token error');
        }

        $token = $tokenResp->json('access_token');

        // NOTE: Real providers have different endpoints and required fields for STK push.
        // Adapt this block to the provider's API (e.g., MTN Momo has a 'requesttopay' endpoint with specific headers).
        $resp = Http::withToken($token)->post($endpoint . '/payments', [
            'phone' => $phone,
            'amount' => $amount,
            'callback_url' => $callbackUrl,
            'metadata' => $metadata,
            'provider' => $provider,
        ]);

        if (! $resp->ok()) {
            throw new \Exception('MOMO initiate error: ' . $resp->body());
        }

        $body = $resp->json();

        return [
            'transaction_id' => $body['transaction_id'] ?? $body['txid'] ?? uniqid('momo_'),
            'status' => $body['status'] ?? 'pending',
            'provider_payload' => $body,
        ];
    }
}
