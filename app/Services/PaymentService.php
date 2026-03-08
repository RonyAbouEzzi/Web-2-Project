<?php

namespace App\Services;

use App\Models\ServiceRequest;
use Illuminate\Support\Str;

/**
 * PaymentService
 * Handles card payments (via Stripe) and crypto payments.
 * Replace TODO sections with your actual provider SDK calls.
 */
class PaymentService
{
    public function process(ServiceRequest $serviceRequest, string $method, array $payload): array
    {
        return match ($method) {
            'card'   => $this->processCard($serviceRequest, $payload),
            'crypto' => $this->processCrypto($serviceRequest, $payload),
            default  => ['success' => false, 'message' => 'Unknown payment method.'],
        };
    }

    // ── Card Payment (Stripe) ─────────────────────────────────────
    private function processCard(ServiceRequest $req, array $payload): array
    {
        try {
            // TODO: Replace with actual Stripe integration
            // \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            // $charge = \Stripe\Charge::create([
            //     'amount'   => (int) ($req->service->price * 100),
            //     'currency' => strtolower($req->service->currency),
            //     'source'   => $payload['stripe_token'],
            //     'description' => 'Service Request: ' . $req->reference_number,
            // ]);
            // return ['success' => true, 'transaction_id' => $charge->id];

            // Simulated success for development
            return ['success' => true, 'transaction_id' => 'SIM_CARD_' . Str::upper(Str::random(10))];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ── Crypto Payment ────────────────────────────────────────────
    private function processCrypto(ServiceRequest $req, array $payload): array
    {
        try {
            // TODO: Replace with actual crypto provider (e.g., Coinbase Commerce, NOWPayments)
            // $api     = app(CryptoApiClient::class);
            // $charge  = $api->createCharge([
            //     'name'        => 'Service Request',
            //     'description' => $req->reference_number,
            //     'amount'      => $req->service->price,
            //     'currency'    => $req->service->currency,
            // ]);
            // return ['success' => true, 'transaction_id' => $charge->id];

            // Simulated success for development
            return ['success' => true, 'transaction_id' => 'SIM_CRYPTO_' . Str::upper(Str::random(10))];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ── Currency Conversion ───────────────────────────────────────
    public function convertCurrency(float $amount, string $from, string $to): float
    {
        // TODO: Integrate currency exchange API
        // e.g., https://exchangerate-api.com or https://openexchangerates.org
        // $rate = Http::get("https://api.exchangerate-api.com/v4/latest/{$from}")
        //             ->json("rates.{$to}");
        // return round($amount * $rate, 2);

        return $amount; // fallback: same amount
    }
}
