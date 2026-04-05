<?php

namespace App\Services;

use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Exception\ApiErrorException;
use Stripe\HttpClient\CurlClient;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        Stripe::setMaxNetworkRetries(0);
        CurlClient::instance()->setTimeout(30);
        CurlClient::instance()->setConnectTimeout(10);
    }

    public function process(ServiceRequest $serviceRequest, string $method, array $payload): array
    {
        return match ($method) {
            'card'   => $this->processCard($serviceRequest, $payload),
            'crypto' => $this->processCrypto($serviceRequest, $payload),
            default  => ['success' => false, 'message' => 'Unknown payment method.'],
        };
    }

    // ── Card Payment (Stripe Checkout Session) ─────────────────────
    private function processCard(ServiceRequest $req, array $payload): array
    {
        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => strtolower($req->service->currency ?? 'usd'),
                        'product_data' => [
                            'name'        => $req->service->name,
                            'description' => 'Service Request: ' . $req->reference_number,
                        ],
                        'unit_amount' => (int) ($req->service->price * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode'        => 'payment',
                'success_url' => route('citizen.payment.success', $req) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('citizen.payment.cancel', $req),
                'metadata'    => [
                    'service_request_id' => $req->id,
                    'reference_number'   => $req->reference_number,
                ],
            ]);

            return [
                'success'      => true,
                'redirect_url' => $session->url,
                'session_id'   => $session->id,
            ];
        } catch (ApiErrorException $e) {
            return ['success' => false, 'message' => 'Stripe error: ' . $e->getMessage()];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Could not connect to payment processor. Please try again.'];
        }
    }

    // ── Verify Stripe Session ──────────────────────────────────────
    public function verifyStripeSession(string $sessionId): array
    {
        try {
            $session = StripeSession::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                return [
                    'success'        => true,
                    'transaction_id' => $session->payment_intent,
                ];
            }

            return ['success' => false, 'message' => 'Payment not completed.'];
        } catch (ApiErrorException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ── Crypto Payment (Simulated) ─────────────────────────────────
    private function processCrypto(ServiceRequest $req, array $payload): array
    {
        try {
            $crypto   = $payload['crypto_currency'] ?? 'BTC';
            $usdPrice = $req->service->price;

            // Generate a deterministic wallet address for demo purposes
            $walletAddresses = [
                'BTC'  => '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
                'ETH'  => '0x32Be343B94f860124dC4fEe278FDCBD38C102D88',
                'USDT' => 'TN2Yq6HwZvdJnU5r5LoF5hN8uR5JX1CQJD',
            ];

            $cryptoAmount = $this->convertToCrypto($usdPrice, $crypto);

            return [
                'success'        => true,
                'requires_confirmation' => true,
                'wallet_address' => $walletAddresses[$crypto] ?? $walletAddresses['BTC'],
                'crypto_amount'  => $cryptoAmount,
                'crypto_currency'=> $crypto,
                'transaction_id' => 'CRYPTO_' . Str::upper(Str::random(10)),
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ── Confirm crypto payment (manual confirmation) ───────────────
    public function confirmCrypto(ServiceRequest $req, string $txHash): array
    {
        // In production, verify the transaction on-chain
        // For this project, we accept any non-empty tx hash
        if (empty($txHash)) {
            return ['success' => false, 'message' => 'Transaction hash is required.'];
        }

        return [
            'success'        => true,
            'transaction_id' => $txHash,
        ];
    }

    // ── Convert USD to Crypto (approximate) ────────────────────────
    private function convertToCrypto(float $usdAmount, string $crypto): string
    {
        // Approximate rates for demo purposes
        $rates = [
            'BTC'  => 65000,
            'ETH'  => 3500,
            'USDT' => 1,
        ];

        $rate = $rates[$crypto] ?? 1;
        $amount = $usdAmount / $rate;

        return number_format($amount, $crypto === 'USDT' ? 2 : 8);
    }

    // ── Currency Conversion ────────────────────────────────────────
    public function convertCurrency(float $amount, string $from, string $to): float
    {
        if ($from === $to) {
            return $amount;
        }

        $cacheKey = "exchange_rate_{$from}_{$to}";

        $rate = Cache::remember($cacheKey, 3600, function () use ($from, $to) {
            try {
                $response = Http::timeout(5)->get(
                    "https://api.exchangerate-api.com/v4/latest/{$from}"
                );

                if ($response->successful()) {
                    return $response->json("rates.{$to}");
                }
            } catch (\Exception $e) {
                // Fail silently, use fallback
            }

            // Fallback rates
            $fallback = [
                'USD' => ['LBP' => 89500, 'EUR' => 0.92],
                'LBP' => ['USD' => 0.0000112, 'EUR' => 0.0000103],
                'EUR' => ['USD' => 1.09, 'LBP' => 97000],
            ];

            return $fallback[$from][$to] ?? 1;
        });

        return round($amount * $rate, 2);
    }
}
