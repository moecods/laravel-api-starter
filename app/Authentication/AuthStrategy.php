<?php

namespace App\Authentication;

interface AuthStrategy
{
    public function authenticate();

    public function register();
}
