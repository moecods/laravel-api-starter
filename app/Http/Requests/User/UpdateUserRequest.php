<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'email' => 'email|unique:users',
            'password' => 'string|min:8',
        ];
    }
}
