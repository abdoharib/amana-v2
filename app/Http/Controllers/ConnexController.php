<?php

namespace App\Http\Controllers;

use App\services\ConnexApiService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Carbon\Carbon;

// Models (adjust namespaces if different)
use App\Models\User;
use App\Models\FcmToken;

class ConnexController extends Controller
{
    public function __construct(private ConnexApiService $connex)
    {
    }

    /** Consistent phone normalization: 218 + local (no leading 0) */
    private function normalizeMsisdn(string $raw): string
    {
        $digits = preg_replace('/\D+/', '', $raw);
        if (Str::startsWith($digits, '218')) {
            $rest = substr($digits, 3);
            if (Str::startsWith($rest, '0'))
                $rest = substr($rest, 1);
            return '218' . $rest;
        }
        if (Str::startsWith($digits, '0'))
            $digits = substr($digits, 1);
        return '218' . $digits;
    }

    // ------------------- Endpoints -------------------

    // GET /api/client/request-protected-script
    public function protectedScript()
    {
        $data = $this->connex->getProtectedScript();

        abort_unless(
            data_get($data, 'transaction_identify') && data_get($data, 'dcbprotect'),
            502,
            'Protected script payload is incomplete'
        );

        return response()->json($data);
    }

    // POST /api/client/login  (request OTP)
    public function login(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'min:6', 'max:20'],
            'transaction_identify' => ['required', 'string'],
            'otp_signature' => ['nullable', 'string'],
        ]);

        $resp = $this->connex->loginRequest(
            $this->normalizeMsisdn($validated['phone']),
            $validated['transaction_identify'],
            $validated['otp_signature'] ?? null
        );

        // Optional: anti-fraud code 300 handling
        $messageCode = data_get($resp, 'messageCode') ?? data_get($resp, 'data.messageCode');
        if ((string) $messageCode === '300') {
            throw ValidationException::withMessages(['phone' => 'Login blocked by fraud checks']);
        }

        return response()->json(['ok' => true, 'data' => $resp]);
    }

    // POST /api/client/otp-confirm  ✅ upsert user + issue token
    public function otpConfirm(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'otp' => ['required', 'digits:4'],
            'fcm_token' => ['nullable', 'string'], // optional from FE
        ]);

        $msisdn = $this->normalizeMsisdn($validated['phone']);

        // 1) Verify with Connex
        $resp = $this->connex->otpConfirm($msisdn, $validated['otp']);


        // 3) Upsert local user by phone_number

        $success = $resp['success'] ?? [];

        $user = User::updateOrCreate(
            ['phone_number' => $msisdn],
            [
                'subscription_status' => $success['status'] ?? null,
                'subscription_plan' => $success['operator'] ?? null,
                'subscription_expires_at' => $success['expiration_date'] ?? null,
            ]
        );

        // 4) Persist (or add) FCM token
        if (!empty($validated['fcm_token'])) {
            FcmToken::updateOrCreate(
                ['user_id' => $user->id, 'token' => $validated['fcm_token']],
                []
            );
        }

        // 5) Issue Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'ok' => true,
            'token' => $token,
            'user' => $user,
            'data' => $resp, // raw provider payload if FE needs it
        ]);
    }

    // GET /api/client/subscriber?phone=...
    public function subscriber(Request $request)
    {
        $request->validate(['phone' => ['required', 'string']]);

        $msisdn = $this->normalizeMsisdn($request->query('phone'));
        $resp = $this->connex->subscriberDetails($msisdn);

        // Keep DB snapshot fresh whenever we fetch
        if ($user = User::where('phone_number', $msisdn)->first()) {

            $user->subscription_status = $resp['success']['details']['status'];
            $user->subscription_plan = $resp['success']['details']['subscription_name'];
            $user->subscription_expires_at = $resp['success']['details']['expiration_date'];

            $user->save();
        }

        return response()->json(['ok' => true, 'data' => $resp]);
    }

    // POST /api/client/activate  (request OTP for activation)
    public function activate(Request $request)
    {
        $validated = $request->validate(['phone' => ['required', 'string']]);

        $msisdn = $this->normalizeMsisdn($validated['phone']);
        $resp = $this->connex->activationRequest($msisdn);

        return response()->json(['ok' => true, 'data' => $resp]);
    }

    // POST /api/client/activate-confirm  (also sync local snapshot)
    public function activateConfirm(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'otp' => ['required', 'digits:4'],
        ]);

        $msisdn = $this->normalizeMsisdn($validated['phone']);
        $resp = $this->connex->activationConfirm($msisdn, $validated['otp']);

        return response()->json(['ok' => true, 'data' => $resp]);
    }

    // POST /api/client/unsubscribe  (request OTP)
    public function unsubscribe(Request $request)
    {
        $validated = $request->validate(['phone' => ['required', 'string']]);

        $msisdn = $this->normalizeMsisdn($validated['phone']);
        $resp = $this->connex->unsubscribeRequest($msisdn);

        return response()->json(['ok' => true, 'data' => $resp]);
    }

    // POST /api/client/unsubscribe-confirm  (also sync local snapshot)
    public function unsubscribeConfirm(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'otp' => ['required', 'digits:4'],
        ]);

        $msisdn = $this->normalizeMsisdn($validated['phone']);
        $resp = $this->connex->unsubscribeConfirm($msisdn, $validated['otp']);



        return response()->json(['ok' => true, 'data' => $resp]);
    }

    // POST /api/client/test-subscriber  (test endpoint for subscriber details)
    public function testSubscriber(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['nullable', 'string'],
            'skip_db' => ['nullable', 'boolean'],
        ]);

        $rawPhone = $validated['phone'] ?? '0920217668';
        $skipDb = $validated['skip_db'] ?? false;

        // Normalize phone number
        $msisdn = $this->normalizeMsisdn($rawPhone);

        $result = [
            'test_info' => [
                'raw_phone' => $rawPhone,
                'normalized' => $msisdn,
            ]
        ];

        // Find or create user (only if database operations are not skipped)
        if (!$skipDb) {
            try {
                $user = User::firstOrCreate(
                    ['phone_number' => $msisdn],
                    [
                        'name' => 'Test User',
                        'email' => 'test_' . time() . '@example.com',
                        'password' => bcrypt('password123'),
                    ]
                );

                $result['user'] = [
                    'id' => $user->id,
                    'phone_number' => $user->phone_number,
                    'was_newly_created' => $user->wasRecentlyCreated,
                ];
            } catch (\Exception $e) {
                $result['user'] = [
                    'error' => 'Database not available',
                    'message' => $e->getMessage(),
                ];
            }
        } else {
            $result['user'] = ['skipped' => true];
        }

        // Call subscriberDetails API
        try {
            $apiResponse = $this->connex->subscriberDetails($msisdn);

            // Extract specific fields
            $status = $apiResponse['success']['details']['status'] ?? 'N/A';
            $expirationDate = $apiResponse['success']['details']['expiration_date'] ?? 'N/A';
            $subscriptionName = $apiResponse['success']['details']['subscription_name'] ?? 'N/A';

            $result['api_response'] = $apiResponse;
            $result['extracted_data'] = [
                'status' => $status,
                'subscription_name' => $subscriptionName,
                'expiration_date' => $expirationDate,
            ];

            return response()->json([
                'ok' => true,
                'message' => 'Test completed successfully',
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Failed to fetch subscriber details',
                'error' => $e->getMessage(),
                'data' => $result,
            ], 500);
        }
    }
}
