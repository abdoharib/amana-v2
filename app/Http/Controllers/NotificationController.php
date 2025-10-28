<?php

namespace App\Http\Controllers;

use App\Models\FcmToken;
use App\Models\Notification;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return request()->user()->notifications()->latest()->get();
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }

    public function send(Request $request, FirebaseNotificationService $fcm)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'user_ids' => 'required|string',
        ]);

        $userIds = explode(',', $request->user_ids);

        $deviceTokens = FcmToken::whereIn('user_id', $userIds)->pluck('token', 'user_id');
        // dd($deviceTokens);
        if ($deviceTokens->isEmpty()) {
          return response()->json([
                'success' => false,
                'message' => 'no users found with the provided IDs or no device tokens available.'
            ], 404);
        }

        $payloadData = ['custom_key' => 'custom_value']; // optional

        // Send notifications
        $fcm->sendToMultipleDevices($deviceTokens->values()->toArray(), $request->title, $request->body, $payloadData);

        // Save each one
        foreach ($deviceTokens as $userId => $token) {
            Notification::create([
                'user_id' => $userId,
                'title' => $request->title,
                'body' => $request->body,
                'data' => $payloadData,
            ]);
        }
        //return json response
        return response()->json([
            'message' => 'Notification sent successfully',
            'data' => $deviceTokens->keys()->toArray(),
        ]);
         
    }
}
