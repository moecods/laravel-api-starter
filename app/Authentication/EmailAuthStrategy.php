<?php

namespace App\Authentication;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmailAuthStrategy implements AuthStrategy
{
    private $email;
    private $password;
    private ?string $name;

    public function __construct($email, $password, $name = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
    }

    public function authenticate()
    {
        return auth()->attempt([
            'email' => $this->email,
            'password' => $this->password
        ]);
    }

    public function register()
    {
        Validator::validate([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $password = bcrypt($this->password);
        return User::query()->create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $password,
        ]);
    }
}
