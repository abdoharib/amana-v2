<?php
namespace App\Actions;

use App\Models\Otp;

class VerifyOtp {

    public function execute($phone,$otp)
    {
        $otp = Otp::where('phone_number', $phone)->first();

        if (!$otp || $otp->code != $otp) {
            return response()->json(['error' => 'رمز التحقق غير صالح.'], 401);
        }

        $otpValidDuration = 5; // minutes
        if (now()->diffInMinutes($otp->created_at) > $otpValidDuration) {
            return response()->json(['error' => 'انتهت صلاحية رمز التحقق.'], 401);
        }

        return true;
    }

}