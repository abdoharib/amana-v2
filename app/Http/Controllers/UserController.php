<?php

namespace App\Http\Controllers;

use App\Services\ConnexApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class UserController extends Controller
{
    private ConnexApiService $connex;
    public function __construct()
    {
        $this->connex = new ConnexApiService();
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
                ])
                ->cookie('authToken', $token, 60 * 24 * 7); // 7 days

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
                ->cookie('authToken', $token, 60 * 24 * 7); // 7 days

        } catch (\Exception $e) {
            return view('user-profile', [
                'user' => null,
                'subscription' => null,
                'error' => 'حدث خطأ أثناء تحميل الملف التجريبي. الخطأ: ' . $e->getMessage(),
            ]);
        }
    }
}

