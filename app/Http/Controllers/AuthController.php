<?php

namespace App\Http\Controllers;

use App\Actions\VerifyOtp;
use App\Exceptions\BannedUserException;
use App\Exceptions\InvalidLoginException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendVerificationCodeRequest;
use App\Models\FcmToken;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function sendVerificationCode(SendVerificationCodeRequest $request)
    {
        // Since validation is handled in the Request file, we can skip manual validation here

        // Generate a random OTP (6 digits)
        $otp_code = random_int(100000, 999999);

        // Store OTP in the database (or cache)
        $otp = new Otp();
        $otp->phone_number = $request->phone_number;
        $otp->code = $otp_code;
        $otp->save();


        // Mock SMS sending logic
        // Example: SendSMS::to($user->phone_number)->send($otp);

        return response()->json([
            'message' => 'تم إرسال رمز التحقق بنجاح.',
            'phone_number' => $request->phone_number,
        ]);
    }

    public function login(LoginRequest $request, VerifyOtp $verifyOtp)
    {
        // Verify OTP

        $verifyOtp->execute($request->phone_number, $request->otp);

        // Find user by phone_number number
        $user = User::where('phone_number', $request->phone_number)->first();

        if(!$user){
            $user = User::create([
                'phone_number' => $request->phone_number,
            ]);
        }
        // Create a new token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Save FCM token
        FcmToken::updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $request->fcm_token]
        );

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function loginAdmin(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('phone_number', $request->phone_number)->first();;
        if (!($user->is_activated == 1)) throw new BannedUserException();


        if (!$user || !Hash::check($request->password, $user->password)) {
            $msg = __('auth.failed');
            return response()->json(['message' => $msg], 401);
        }
        $token = $user->createToken("token")->plainTextToken;
        FcmToken::updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $request->fcm_token]
        );
        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'تم تسجيل الخروج'
        ], 200);
    }
}
