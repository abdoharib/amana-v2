<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Str;

class ConnexApiService
{
    private string $baseUrl;
    private string $email;
    private string $password;
    private string $deviceType;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.connex.base_url'), '/');
        $this->email = config('services.connex.email');
        $this->password = config('services.connex.password');
        $this->deviceType = config('services.connex.device_type', 'web');
        $this->timeout = (int) config('services.connex.timeout', 20);
    }

    /** ---------------- Core HTTP ---------------- */
    private function http(?string $token = null)
    {
        $client = Http::timeout($this->timeout)->baseUrl($this->baseUrl);

        if ($token) {
            $client = $client->withToken($token);
        }

        return $client->acceptJson();
    }

    /** ---------------- Auth Token ---------------- */
    public function ensureToken(): string
    {
        return Cache::remember('connex.token', now()->addMinutes(50), function () {
            return $this->loginAndGetToken();
        });
    }

    private function loginAndGetToken(): string
    {
        $resp = $this->http()->post('/auth-login', [
            'email' => $this->email,
            'password' => $this->password,
        ])->throw();

        // Adjust to the exact token field name from Connex, e.g. data.token / access_token


        return data_get($resp->json(), 'data.access_token')

            ?? throw new \RuntimeException('Connex: token not found in Auth Login response');
    }

    /** If unauthorized, refresh token once and retry the given closure */
    private function withTokenRetry(callable $fn)
    {
        $token = $this->ensureToken();
        try {
            return $fn($token);
        } catch (RequestException $e) {
            if ($e->response && $e->response->status() === 401) {
                Cache::forget('connex.token');
                $token = $this->ensureToken();
                return $fn($token);
            }
            throw $e;
        }
    }

    /** ---------------- Connex Endpoints ---------------- */

    // 1) Get protected script (anti-fraud)
    public function getProtectedScript(): array
    {
        return $this->withTokenRetry(function ($token) {
            $resp = $this->http($token)->get('/protected-script', [
                'targeted_element' => '#cta_button',
            ])->throw();


            return [
                // expected keys, adjust names to match the guide exactly
                'transaction_identify' => data_get($resp->json(), 'success.transaction_identify'),
                'dcbprotect' => data_get($resp->json(), 'success.dcbprotect'),
            ];
        });
    }

    // 2) Login (request OTP)
    public function loginRequest(string $phone, string $transactionIdentify, ?string $otpSignature = null): array
    {
        return $this->withTokenRetry(function ($token) use ($phone, $transactionIdentify, $otpSignature) {
            $payload = [
                'msisdn' => $phone,                     // or 'phone'
                'device_type' => $this->deviceType,
                'transaction_identify' => $transactionIdentify,
            ];
            if ($otpSignature)
                $payload['otp_signature'] = $otpSignature;

            $resp = $this->http($token)->post('/login', $payload)->throw();
            return $resp->json();
        });
    }

    // 3) Confirm OTP for login
    public function otpConfirm(string $phone, string $otp): array
    {
        return $this->withTokenRetry(function ($token) use ($phone, $otp) {
            $resp = $this->http($token)->post('/login-confirm', [
                'msisdn' => $phone,
                'otp' => $otp,
                'device_type' => $this->deviceType,
            ])->throw();

            return $resp->json();
        });
    }

    // 4) Subscriber Details
    public function subscriberDetails(string $phone): array
    {
        return $this->withTokenRetry(function ($token) use ($phone) {
            $resp = $this->http($token)->get('/subscriber-details', [
                'msisdn' => $phone,
            ])->throw();

            return $resp->json();
        });
    }

    // Optional: Transactions
    public function subscriberTransactions(string $phone): array
    {
        return $this->withTokenRetry(function ($token) use ($phone) {
            $resp = $this->http($token)->get('/subscriber/transactions', [
                'msisdn' => $phone,
            ])->throw();

            return $resp->json();
        });
    }

    // 5) Activate (request OTP)
    public function activationRequest(string $phone): array
    {

        return $this->withTokenRetry(function ($token) use ($phone) {
            $resp = $this->http($token)->post('/subscription-activation', [
                'msisdn' => $phone,
                'device_type' => $this->deviceType,
            ])->throw();

            return $resp->json();
        });
    }

    // 6) Activate confirm
    public function activationConfirm(string $phone, string $otp): array
    {
        return $this->withTokenRetry(function ($token) use ($phone, $otp) {
            $resp = $this->http($token)->post('/subscription-activation-confirm', [
                'msisdn' => $phone,
                'otp' => $otp,
                'device_type' => $this->deviceType,
            ])->throw();

            return $resp->json();
        });
    }

    // 7) Unsubscribe (request OTP)
    public function unsubscribeRequest(string $phone): array
    {
        return $this->withTokenRetry(function ($token) use ($phone) {
            $resp = $this->http($token)->post('/unsubscribe', [
                'msisdn' => $phone,
                'device_type' => $this->deviceType,
            ])->throw();

            return $resp->json();
        });
    }

    // 8) Unsubscribe confirm
    public function unsubscribeConfirm(string $phone, string $otp): array
    {
        return $this->withTokenRetry(function ($token) use ($phone, $otp) {
            $resp = $this->http($token)->post('/unsubscribe-confirm', [
                'msisdn' => $phone,
                'otp' => $otp,
                'device_type' => $this->deviceType,
            ])->throw();

            return $resp->json();
        });
    }
}
