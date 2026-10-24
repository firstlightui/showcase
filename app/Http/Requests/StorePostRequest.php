<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|min:3|max:80',
            'body' => 'required|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            'body.min' => 'Write at least a sentence.',
        ];
    }
}
