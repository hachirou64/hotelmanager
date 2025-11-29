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

        // Real MOMO integration based on provider
        return $this->initiateRealPayment($phone, $amount, $callbackUrl, $metadata, $provider);
    }

    /**
     * Initiate real MOMO payment with provider-specific API calls.
     */
    private function initiateRealPayment(string $phone, float $amount, string $callbackUrl, array $metadata = [], string $provider = 'mtn'): array
    {
        $endpoint = config('momo.endpoint');
        $clientId = config('momo.client_id');
        $clientSecret = config('momo.client_secret');

        if (!$endpoint || !$clientId || !$clientSecret) {
            throw new \Exception('MOMO credentials not configured. Please set MOMO_ENDPOINT, MOMO_CLIENT_ID, and MOMO_CLIENT_SECRET in .env');
        }

        // Get access token
        $tokenResp = Http::withBasicAuth($clientId, $clientSecret)->post($endpoint . '/oauth/token', [
            'grant_type' => 'client_credentials',
        ]);

        if (!$tokenResp->ok()) {
            throw new \Exception('Failed to get MOMO access token: ' . $tokenResp->body());
        }

        $token = $tokenResp->json('access_token');

        // Provider-specific STK push implementation
        switch (strtolower($provider)) {
            case 'mtn':
                return $this->initiateMtnPayment($token, $endpoint, $phone, $amount, $callbackUrl, $metadata);
            case 'moov':
                return $this->initiateMoovPayment($token, $endpoint, $phone, $amount, $callbackUrl, $metadata);
            case 'celtis':
                return $this->initiateCeltisPayment($token, $endpoint, $phone, $amount, $callbackUrl, $metadata);
            default:
                throw new \Exception("Unsupported MOMO provider: {$provider}");
        }
    }

    /**
     * Initiate MTN MOMO payment (STK push).
     */
    private function initiateMtnPayment(string $token, string $endpoint, string $phone, float $amount, string $callbackUrl, array $metadata): array
    {
        $resp = Http::withToken($token)->post($endpoint . '/collection/v1_0/requesttopay', [
            'amount' => (string)$amount,
            'currency' => 'XAF', // Adjust currency as needed
            'externalId' => uniqid('mtn_'),
            'payer' => [
                'partyIdType' => 'MSISDN',
                'partyId' => $phone,
            ],
            'payerMessage' => 'Hotel Reservation Payment',
            'payeeNote' => 'Payment for reservation',
        ]);

        if (!$resp->ok()) {
            throw new \Exception('MTN MOMO payment initiation failed: ' . $resp->body());
        }

        $body = $resp->json();

        return [
            'transaction_id' => $body['financialTransactionId'] ?? uniqid('mtn_'),
            'status' => 'pending',
            'provider_payload' => $body,
        ];
    }

    /**
     * Initiate Moov MOMO payment (STK push).
     */
    private function initiateMoovPayment(string $token, string $endpoint, string $phone, float $amount, string $callbackUrl, array $metadata): array
    {
        // Moov API structure (adjust based on actual API documentation)
        $resp = Http::withToken($token)->post($endpoint . '/api/v1/payments', [
            'amount' => $amount,
            'phone' => $phone,
            'callback_url' => $callbackUrl,
            'metadata' => $metadata,
        ]);

        if (!$resp->ok()) {
            throw new \Exception('Moov MOMO payment initiation failed: ' . $resp->body());
        }

        $body = $resp->json();

        return [
            'transaction_id' => $body['transaction_id'] ?? uniqid('moov_'),
            'status' => 'pending',
            'provider_payload' => $body,
        ];
    }

    /**
     * Initiate Celtis MOMO payment (STK push).
     */
    private function initiateCeltisPayment(string $token, string $endpoint, string $phone, float $amount, string $callbackUrl, array $metadata): array
    {
        // Celtis API structure (adjust based on actual API documentation)
        $resp = Http::withToken($token)->post($endpoint . '/api/payments/initiate', [
            'amount' => $amount,
            'phone_number' => $phone,
            'callback_url' => $callbackUrl,
            'description' => 'Hotel Reservation Payment',
        ]);

        if (!$resp->ok()) {
            throw new \Exception('Celtis MOMO payment initiation failed: ' . $resp->body());
        }

        $body = $resp->json();

        return [
            'transaction_id' => $body['transaction_id'] ?? uniqid('celtis_'),
            'status' => 'pending',
            'provider_payload' => $body,
        ];
    }
}
