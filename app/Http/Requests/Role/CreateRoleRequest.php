<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique(config('permission.table_names')['roles'], 'name'),
            ],
            'permissions' => 'nullable|array',
            'permissions.*' => [
                'int',
                'min:1',
                Rule::exists(config('permission.table_names')['permissions'], 'id'),
            ],
        ];
    }
}
