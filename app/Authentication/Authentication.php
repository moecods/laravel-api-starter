<?php

namespace App\Authentication;

use App\Models\User;
use Ichtrojan\Otp\Otp;

class Authentication
{
    /**
     * @var mixed|object
     */
    private bool $otpIsValid;

    public bool $isAuthenticated = false;

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    public function ifUnauthenticated(\Closure $param): static
    {
        if (! $this->isAuthenticated()) {
            abort($param());
        }

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

    public function getUser(): User
    {
        return $this->user;
    }

    public function checkOtp(mixed $identify, mixed $otp): static
    {
        $this->otpIsValid = (bool) (new Otp())->validate($identify, $otp);

        return $this;
    }

    public function otpIsValid(): bool
    {
        return $this->otpIsValid;
    }

    public function ifOtpUnVerified(\Closure $param): static
    {
        if (! $this->otpIsValid()) {
            abort($param());
        }

        return $this;
    }
}
