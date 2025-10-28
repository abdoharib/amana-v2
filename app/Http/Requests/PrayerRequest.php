<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrayerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'required|string',
            'image' => 'sometimes|file|mimes:png,jpg,jpeg|max:2048'
        ];

        if ($this->isMethod('post')) { // Creating a new record
            $rules['image'] = 'required|file|mimes:png,jpg,jpeg|max:2048';
        }

        return $rules;
    }
}
