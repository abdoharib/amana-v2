<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Implement authorization logic based on your requirements (e.g., only guardians can create)
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required',
            'guardian_id' => 'nullable|exists:users,id', 
            'kid_id' => 'required|exists:kids,id',
        ];
    }
}