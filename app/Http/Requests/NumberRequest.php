<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NumberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Change to false if authorization is required
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'number' => 'required|integer|unique:numbers,number' . ($this->number ? ',' . $this->number : ''),
            'image' => 'sometimes|file|mimes:png,jpg,jpeg|max:2048',
            'audio' => 'sometimes'
        ];

        if ($this->isMethod('post')) { // Creating a new record
            $rules['image'] = 'required|file|mimes:png,jpg,jpeg|max:2048';
            $rules['audio'] = 'required';
        }

        return $rules;
    }
}
