<?php

namespace App\Repositories;

use App\Models\User;

class UserRepo extends BaseRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function create(array $data): mixed
    {
        $data['password'] = bcrypt($data['password']);

        return parent::create($data);
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return parent::update($id, $data);
    }
}
