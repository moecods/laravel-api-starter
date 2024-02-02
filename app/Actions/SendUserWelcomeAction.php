<?php

namespace App\Actions;

use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendUserWelcomeAction
{
    public function execute(User $user): void
    {
        Mail::to($user->email)->send(new WelcomeUserMail($user));
    }
}
