<?php

namespace App\Authentication;

interface AuthStrategy
{
    public function authenticate(string $email, string $password);

    public function register(string $name, string $email, string $password);
}
