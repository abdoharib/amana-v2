<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LetterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Set to false if you need authentication checks
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'letter' => 'required|string|max:10',
            'word' => 'required|string',
            'word_audio' => 'nullable|file',
            'image' => 'sometimes|file|mimes:png,jpg,jpeg|max:2048',
            'audio' => 'sometimes|file'
        ];

        if ($this->isMethod('post')) { // Creating a new record
            $rules['image'] = 'required|file|mimes:png,jpg,jpeg|max:2048';
            $rules['audio'] = 'required|file';
        }

        return $rules;
    }
}
