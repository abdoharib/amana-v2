<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
            'title' => 'sometimes|string|max:255',
            'image' => 'sometimes',
            'category_id' => 'sometimes|integer',
            'educational_content_id' => 'sometimes|integer',
            'deletedIds' => 'sometimes',
            'details' => 'sometimes|array', // Validate the details array
            'details.*.id' => 'nullable|integer',
            'details.*.title' => 'required_with:details|string|max:255',
            'details.*.description' => 'required_with:details|string',
            'details.*.audio' => 'nullable',
            'details.*.image' => 'nullable',
        ];
    }
}
