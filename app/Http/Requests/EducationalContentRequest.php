<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EducationalContentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Change to authorization logic if needed
    }

    public function rules()
    {

        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'image_path' => 'nullable|string|max:255',
            'type' => 'required|string|max:255',
            'image' => 'nullable',
            'education_stage_id' => 'required|exists:education_stages,id',
        ];
    }
}
