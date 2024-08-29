<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\RegisterNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Events\VerificationCodeEvent;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\verify\VerificationCodeNotification;

class VerificationCodeListener
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
    public function handle(VerificationCodeEvent $event): void
    {
        $event->user->notify(new RegisterNotification($event->user, $event->code));
    }
}
