<?php

namespace App\Listeners;

use App\Actions\SendUserVerificationCodeAction;
use App\Events\UserRegisteredEvent;

class SendUserVerificationCodeListener
{
    /**
     * Handle the event.
     */
    public function handle(UserRegisteredEvent $event): void
    {
        app(SendUserVerificationCodeAction::class)->execute($event->user);
    }
}
