<?php

namespace App\Providers;

use App\Events\newUserNotify;
use App\Events\UserEvent;
use App\Events\VerificationCodeEvent;
use App\Listeners\sendMail;
use App\Listeners\sendNotifyMail;
use App\Listeners\VerificationCodeListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */


    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            ],
        VerificationCodeEvent::class=>[
            VerificationCodeListener::class,
        ]
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
