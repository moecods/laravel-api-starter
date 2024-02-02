<?php

namespace App\Listeners;

use App\Actions\SendWelcomeUserAction;
use App\Events\UserRegisteredEvent;

class SendWelcomeEmailListener
{
    public function handle(UserRegisteredEvent $event): void
    {
        app(SendWelcomeUserAction::class)->execute($event->user);
    }
}
