<?php

namespace App\Authentication;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EmailAuth extends Authentication
{
    public $user;

    private $token;

    public function __construct()
    {
    }

    public function authenticate($email, $password)
    {
        $this->isAuthenticated = Auth::guard('web')->attempt([
            'email' => $email,
            'password' => $password,
        ]);

        $this->user = auth()->user();

        return $this;
    }

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    public function register(string $name, string $email, string $password)
    {
        $this->user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
