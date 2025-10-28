<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'title' => 'nullable|string|max:255',
            'body' => 'string',
            'media' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video' => 'nullable|file|mimetypes:video/mp4,video/quicktime|max:10000',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:post_categories,id',
            'is_pinned' => 'boolean',
            'is_published' => 'boolean',
        ];
    }
}
