<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:1|max:255',
            'content' => 'required|string|min:1',
        ];
    }
}
