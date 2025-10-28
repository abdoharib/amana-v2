<?php

namespace App\Http\Controllers;

use App\Actions\UpdateUser;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\GuardianResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile()
    {
        return response()->json(new GuardianResource(request()->user()));
    }

    public function update(ProfileRequest $request, UpdateUser $updateUser)
    {
        $guardian = $updateUser->execute(request()->user(), $request);
        return response()->json($guardian);
    }

    public function updatePhone(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'old_phone' => 'required|string',
            'new_phone' => 'required|string|unique:users,phone_number', // Ensure the new phone is unique
        ]);

        // Get the authenticated user
        /** @var User $user */
        $user = request()->user();

        // Check if the old phone matches the user's current phone number
        if ($user->phone !== $validated['old_phone']) {
            return response()->json([
                'message' => 'رقم الهاتف القديم غير صحيح.',
            ], 400); // Return a bad request response
        }

        // Update the user's phone number
        $user->update([
            'phone_number' => $validated['new_phone'],
        ]);

        return response()->json([
            'message' => 'تم تحديث رقم الهاتف بنجاح.',
        ]);
    }
}
