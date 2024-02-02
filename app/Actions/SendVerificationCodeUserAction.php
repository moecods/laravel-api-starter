<?php

namespace App\Actions;

use App\Mail\VerificationCodeMail;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Mail;

class SendVerificationCodeUserAction
{
    public $otpResponse;

    /**
     * @throws \Exception
     */
    public function execute(User $user): void
    {
        $this->otpResponse = (new Otp)->generate($user->email, 'numeric', 6, 30);
        Mail::to($user->email)->send(new VerificationCodeMail($user, $this->otpResponse->token));
    }

    public function getOtpResponse()
    {
        return $this->otpResponse;
    }
}
