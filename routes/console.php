<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\ConnexApiService;
use App\Models\User;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('connex:test-subscriber {--skip-db : Skip database operations}', function (ConnexApiService $connex) {
    $rawPhone = '0920217668';
    
    // Normalize phone number (same logic as ConnexController)
    $digits = preg_replace('/\D+/', '', $rawPhone);
    if (Str::startsWith($digits, '218')) {
        $rest = substr($digits, 3);
        if (Str::startsWith($rest, '0')) $rest = substr($rest, 1);
        $msisdn = '218' . $rest;
    } else {
        if (Str::startsWith($digits, '0')) $digits = substr($digits, 1);
        $msisdn = '218' . $digits;
    }
    
    $this->info("Testing subscriber details for phone: {$rawPhone}");
    $this->info("Normalized phone (msisdn): {$msisdn}");
    $this->newLine();
    
    // Find or create user (only if database is available)
    if (!$this->option('skip-db')) {
        try {
            $user = User::firstOrCreate(
                ['phone_number' => $msisdn],
                [
                    'name' => 'Test User',
                    'email' => 'test_' . time() . '@example.com',
                    'password' => 'password123',
                ]
            );
            
            if ($user->wasRecentlyCreated) {
                $this->info("✓ User created with ID: {$user->id}");
            } else {
                $this->info("✓ User found with ID: {$user->id}");
            }
            $this->newLine();
        } catch (\Exception $e) {
            $this->warn("Database not available: " . $e->getMessage());
            $this->warn("Continuing with API test only...");
            $this->newLine();
        }
    }
    
    // Call subscriberDetails
    try {
        $this->info("Calling subscriberDetails API...");
        $result = $connex->subscriberDetails($msisdn);
        
        $this->newLine();
        $this->info("=== API Response ===");
        $this->line(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->newLine();
        
        // Extract specific fields
        $status = $result['success']['details']['status'] ?? 'N/A';
        $expirationDate = $result['success']['details']['expiration_date'] ?? 'N/A';
        $subscriptionName = $result['success']['details']['subscription_name'] ?? 'N/A';
        
        $this->info("=== Extracted Data ===");
        $this->line("Status: {$status}");
        $this->line("Subscription Name: {$subscriptionName}");
        $this->line("Expiration Date: {$expirationDate}");
        
        $this->newLine();
        $this->info("✓ Test completed successfully!");
        
    } catch (\Exception $e) {
        $this->error("Failed to fetch subscriber details!");
        $this->error("Error: " . $e->getMessage());
        return 1;
    }
    
    return 0;
})->purpose('Test Connex subscriberDetails API with phone 0920217668');
