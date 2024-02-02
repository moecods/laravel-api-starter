<?php

namespace App\Listeners;

use App\Actions\SendUserWelcomeAction;
use App\Events\UserRegisteredEvent;

class SendUserWelcomeListener
{
    public function handle(UserRegisteredEvent $event): void
    {
        app(SendUserWelcomeAction::class)->execute($event->user);
    }
}
