<?php

namespace App\Actions;

use App\Mail\UserWelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendUserWelcomeAction
{
    public function execute(User $user): void
    {
        Mail::to($user->email)->send(new UserWelcomeMail($user));
    }
}
