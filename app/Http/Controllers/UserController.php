<?php

namespace App\Http\Controllers;

use App\Services\ConnexApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct(private ConnexApiService $connex)
    {
    }

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
    /**
     * Show user profile with subscription details
     */
    public function showProfile(Request $request)
    {
        try {
            $user = $request->user();
            
            // Get or create auth token for the user
            $token = $user->currentAccessToken();
            if (!$token) {
                // If no current token, create a new one
                $token = $user->createToken('auth_token')->plainTextToken;
            } else {
                // Get the plain text token (for existing tokens, we'll use the token from the request)
                $token = $request->bearerToken();
            }
            
            // Initialize subscription data
            $subscriptionData = null;
            $subscriptionError = null;

            // Try to get subscription details if user has phone number
            if ($user->phone_number) {
                try {
                    
                    $msisdn = $this->normalizeMsisdn($user->phone_number);
                    $subscriberResponse = $this->connex->subscriberDetails($msisdn);
                    
                    // Extract subscription details
                    if (isset($subscriberResponse['success']['details'])) {
                        $details = $subscriberResponse['success']['details'];
                        $subscriptionData = [
                            'status' => $details['status'] ?? 'N/A',
                            'subscription_name' => $details['subscription_name'] ?? 'N/A',
                            'expiration_date' => $details['expiration_date'] ?? 'N/A',
                        ];
                    }
                } catch (\Exception $e) {

                    // dd($e);
                    $subscriptionError = 'تعذر جلب تفاصيل الاشتراك في الوقت الحالي.';
                }
            }

            return response()
                ->view('user-profile', [
                    'user' => $user,
                    'subscription' => $subscriptionData,
                    'subscriptionError' => $subscriptionError,
                ])
                ->cookie('auth_token', $token, 60 * 24 * 7, null, null, true, false); // 7 days, secure=true, httpOnly=false

        } catch (\Exception $e) {
            return view('user-profile', [
                'user' => null,
                'subscription' => null,
                'error' => 'حدث خطأ أثناء تحميل ملفك الشخصي. يرجى المحاولة مرة أخرى لاحقاً.',
            ]);
        }
    }

    /**
     * Show user profile for testing (uses hardcoded user id = 5)
     */
    public function showProfileTest()
    {
        try {
            // Hardcode user with id = 5 for testing
            $user = \App\Models\User::find(5);
            
            if (!$user) {
                return view('user-profile', [
                    'user' => null,
                    'subscription' => null,
                    'error' => 'المستخدم التجريبي رقم 5 غير موجود في قاعدة البيانات. يرجى إنشاء مستخدم برقم 5 أو تحديث الرقم المحدد.',
                ]);
            }
            
            // Create a test token for this user
            $token = $user->createToken('test_auth_token')->plainTextToken;
            
            // Initialize subscription data
            $subscriptionData = null;
            $subscriptionError = null;

            // Try to get subscription details if user has phone number
            if ($user->phone_number) {
                try {
                    $subscriberResponse = $this->connex->subscriberDetails($user->phone_number);
                    
                    // Extract subscription details
                    if (isset($subscriberResponse['success']['details'])) {
                        $details = $subscriberResponse['success']['details'];
                        $subscriptionData = [
                            'status' => $details['status'] ?? 'N/A',
                            'subscription_name' => $details['subscription_name'] ?? 'N/A',
                            'expiration_date' => $details['expiration_date'] ?? 'N/A',
                        ];
                    }
                } catch (\Exception $e) {
                    $subscriptionError = 'تعذر جلب تفاصيل الاشتراك في الوقت الحالي.';
                }
            }

            return response()
                ->view('user-profile', [
                    'user' => $user,
                    'subscription' => $subscriptionData,
                    'subscriptionError' => $subscriptionError,
                    'isTestMode' => true, // Flag to indicate this is test mode
                ])
                ->cookie('auth_token', $token, 60 * 24 * 7, null, null, true, false); // 7 days, secure=true, httpOnly=false

        } catch (\Exception $e) {
            return view('user-profile', [
                'user' => null,
                'subscription' => null,
                'error' => 'حدث خطأ أثناء تحميل الملف التجريبي. الخطأ: ' . $e->getMessage(),
            ]);
        }
    }
}

