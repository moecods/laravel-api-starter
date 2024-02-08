<?php

namespace App\Authentication;

use App\Models\User;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class MobileAuth extends Authentication
{
    public function authenticate($mobile, $password): static
    {
        $user = User::where('mobile', $mobile)->first();

        if ($user && Hash::check($password, $user->password)) {
            $this->isAuthenticated = true;
            $this->user = $user;
        }

        return $this;
    }

    public function register($mobile, $password, $data): static
    {
        $this->user = User::query()->create($data + [
            'name' => $data['name'] ?? $mobile,
            'mobile' => $mobile,
            'password' => $password,
        ]);

        if ($this->otpIsValid()) {
            $this->user->forceFill([
                'mobile_verified_at' => Date::now(),
            ])->save();
        }

        return $this;
    }
}
