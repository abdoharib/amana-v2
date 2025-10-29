<?php

namespace App\Http\Middleware;

use App\Services\ConnexApiService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ConnexSubscribtionCheckMiddleware
{
    protected ConnexApiService $connexApiService;

    public function __construct(ConnexApiService $connexApiService)
    {
        $this->connexApiService = $connexApiService;
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
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the request is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $user = Auth::user();

        // Ensure user has a phone number
        if (empty($user->phone_number)) {
            return response()->json([
                'success' => false,
                'message' => 'User phone number not found'
            ], 400);
        }

        try {
            // Get subscriber details from Connex API
            $subscriberData = $this->connexApiService->subscriberDetails($this->normalizeMsisdn($user->phone_number));

            // Extract subscription status and expiration date based on actual API response structure
            $subscriptionStatus = $subscriberData['success']['details']['status'] ?? null;
            $subscriptionExpiresAt = $subscriberData['success']['details']['expiration_date'] ?? null;


            $isExpired = $subscriptionExpiresAt && strtotime($subscriptionExpiresAt) <= strtotime('today');
            $isCanceled = $subscriptionStatus !== 'active';
            
            // Check if subscription status is not active
            if ($isCanceled && $isExpired) {
                return response()->json([
                    'success' => false,
                    'message' => 'Active subscription required',
                    'subscription_status' => $subscriptionStatus
                ], 401);

            } else if ($isExpired) {
                return response()->json([
                    'success' => false,
                    'message' => 'Active subscription required',
                    'subscription_status' => $subscriptionStatus
                ], 401);
            }

         

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify subscription status',
                'error' => $e->getMessage()
            ], 500);
        }

        return $next($request);
    }
}
