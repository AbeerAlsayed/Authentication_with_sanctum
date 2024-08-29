<?php

namespace App\Listeners;

use App\Events\UserEvent;
use App\Mail\emailMailableForNotify;
use App\Notifications\RegisterNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class sendMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserEvent $event): void
    {
        $event->user->notify(new RegisterNotification());
    }
}
