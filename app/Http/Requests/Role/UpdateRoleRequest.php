<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => Rule::unique(config('permission.table_names')['roles'], 'name')
                ->whereNot('id', $this->role),
            'permissions' => 'nullable|array',
            'permissions.*' => [
                'int',
                'min:1',
                Rule::exists(config('permission.table_names')['permissions'], 'id'),
            ],
        ];
    }
}
