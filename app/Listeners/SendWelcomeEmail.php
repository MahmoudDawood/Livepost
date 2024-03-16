<?php

namespace App\Listeners;

use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use App\Events\Models\User\UserCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->user)
            ->send(new WelcomeMail($event->user));
    }
}
