<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendVerificationCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all users to send the request
    }

    public function rules()
    {
        return [
            'phone_number' => 'required|regex:/^09[124]\d{7}$/|exists:users,phone_number',
        ];
    }

    public function messages()
    {
        return [
            'phone_number.required' => 'رقم الهاتف مطلوب.',
            'phone_number.regex' => 'يجب أن يبدأ رقم الهاتف بـ 091 أو 092 أو 094 وأن يكون مكونًا من 10 أرقام.',
            'phone_number.exists' => 'رقم الهاتف غير موجود في سجلاتنا.',
        ];
    }
}
