<?php

namespace App\Listeners;

use App\Actions\SendVerificationCodeUserAction;
use App\Events\UserRegisteredEvent;

class SendVerificationCodeListener
{
    /**
     * Handle the event.
     */
    public function handle(UserRegisteredEvent $event): void
    {
        app(SendVerificationCodeUserAction::class)->execute($event->user);
    }
}
