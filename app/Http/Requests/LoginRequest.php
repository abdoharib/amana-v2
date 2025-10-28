<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        // Check the type of login (user or admin)
        if ($this->routeIs('admin.login')) {
            return [
                'phone_number' => 'required|regex:/^09[124]\d{7}$/',
                'password' => 'required|min:6',
                'fcm_token' => 'required',
            ];
        }

        // Default rules for user login with OTP
        return [
            'phone_number' => 'required|regex:/^09[124]\d{7}$/',
            'otp' => 'required|digits:6',
            'fcm_token' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'phone_number.required' => 'رقم الهاتف مطلوب.',
            'phone_number.regex' => 'يجب أن يبدأ رقم الهاتف بـ 091 أو 092 أو 094 وأن يكون مكونًا من 10 أرقام.',
            'otp.required' => 'رمز التحقق مطلوب.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'يجب أن تكون كلمة المرور مكونة من 6 أحرف على الأقل.',
            'otp.digits' => 'يجب أن يكون رمز التحقق مكونًا من 6 أرقام بالضبط.',
        ];
    }
}
