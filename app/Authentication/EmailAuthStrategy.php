<?php

namespace App\Authentication;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EmailAuthStrategy implements AuthStrategy
{
    public $user;

    public bool $isAuthenticated = false;

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

    public function createToken()
    {
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function ifUnauthenticated(\Closure $param)
    {
        if (! $this->isAuthenticated) {
            abort($param());
        }

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
