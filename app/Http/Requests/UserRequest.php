<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('admin') ?? $this->route('guardian');
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'phone_number' => ['required', 'regex:/^09[124]\d{7}$/', Rule::unique('users', 'phone_number')->ignore($id),],
            'password' => 'nullable|string|min:8',
            'is_activated' => 'nullable|boolean',
            'image' => 'nullable|max:255',
        ];
    }
}
