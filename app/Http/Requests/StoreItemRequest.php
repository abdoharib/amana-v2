<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'image' => 'required',
            'category_id' => 'nullable|integer',
            'educational_content_id' => 'required',
            'details' => 'required|array', // Validate the details array
            'details.*.title' => 'required|string|max:255',
            'details.*.description' => 'required|string',
            'details.*.audio' => 'nullable',
            'details.*.image' => 'required',
        ];
    }
}
