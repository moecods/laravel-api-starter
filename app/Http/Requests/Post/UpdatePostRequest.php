<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'string|min:1|max:255',
            'content' => 'string|min:1',
        ];
    }
}
