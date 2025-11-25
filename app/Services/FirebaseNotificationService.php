<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        // Initialize Firebase Messaging
        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));
        $this->messaging = $factory->createMessaging();
    }

    /**
     * Send a notification to a single device.
     *
     * @param string $deviceToken
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     */
    public function sendToDevice(string $deviceToken, string $title, string $body, array $data = [])
    {
        try {
            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($data)
                ->withAndroidConfig(AndroidConfig::fromArray([
                    'priority' => 'high',
                ]))
                ->withApnsConfig(ApnsConfig::fromArray([
                    'payload' => [
                        'aps' => ['sound' => 'default'],
                    ],
                ]))
                ->withChangedTarget('token', $deviceToken); // ✅ Replaces deprecated 'withTarget()'

            $this->messaging->send($message);

            return ['success' => true, 'message' => 'Notification sent successfully'];
        } catch (\Exception $e) {
            Log::error('FCM Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Send a notification to multiple devices.
     *
     * @param array $deviceTokens
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     */
    public function sendToMultipleDevices(array $deviceTokens, string $title, string $body, array $data = [])
    {
        try {
            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($data)
                ->withAndroidConfig(AndroidConfig::fromArray([
                    'priority' => 'high',
                ]))
                ->withApnsConfig(ApnsConfig::fromArray([
                    'payload' => [
                        'aps' => ['sound' => 'default'],
                    ],
                ]));

            $response = $this->messaging->sendMulticast($message, $deviceTokens);

            return ['success' => true, 'message' => 'Notifications sent successfully', 'responses' => $response];
        } catch (\Exception $e) {
            Log::error('FCM Multicast Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
