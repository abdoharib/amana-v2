<?php

namespace App\Http\Middleware;

use App\Services\ConnexApiService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ConnexSubscribtionCheckMiddleware
{
    protected ConnexApiService $connexApiService;

    public function __construct(ConnexApiService $connexApiService)
    {
        $this->connexApiService = $connexApiService;
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
        if (empty($user->phone)) {
            return response()->json([
                'success' => false,
                'message' => 'User phone number not found'
            ], 400);
        }

        try {
            // Get subscriber details from Connex API
            $subscriberData = $this->connexApiService->subscriberDetails($user->phone);

            // Extract subscription status and expiration date based on actual API response structure
            $subscriptionStatus = $subscriberData['success']['details']['status'] ?? null;
            $subscriptionExpiresAt = $subscriberData['success']['details']['expiration_date'] ?? null;

            // Attach subscription info to the request for use in controllers
          

            // Optional: Block access if not subscribed
            // Uncomment the following lines if you want to enforce active subscription
            /*
            if ($subscriptionStatus !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Active subscription required',
                    'subscription_status' => $subscriptionStatus,
                    'subscription_expires_at' => $subscriptionExpiresAt
                ], 403);
            }
            */

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
