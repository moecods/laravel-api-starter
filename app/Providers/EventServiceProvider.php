<?php

namespace App\Providers;

use App\Events\UserRegisteredEvent;
use App\Listeners\SendVerificationCodeListener;
use App\Listeners\SendWelcomeEmailListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegisteredEvent::class => [
            SendWelcomeEmailListener::class,
            SendVerificationCodeListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
