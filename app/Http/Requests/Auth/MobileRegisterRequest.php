<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class MobileRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => 'required|string',
            'code' => 'required|string',
        ];
    }
}
