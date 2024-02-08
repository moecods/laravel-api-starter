<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class MobileLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => 'required|string',
            'password' => 'required|min:8',
        ];
    }
}
