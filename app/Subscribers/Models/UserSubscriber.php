<?php

namespace App\Subscribers\Models;

use Illuminate\Events\Dispatcher;
use App\Listeners\SendWelcomeEmail;
use App\Events\Models\User\UserCreated;
use App\Events\Models\User\UserUpdated;

class UserSubscriber {
    public function subscribe(Dispatcher $dispatcher) {
        $dispatcher->listen(UserCreated::class, SendWelcomeEmail::class);
    }
}