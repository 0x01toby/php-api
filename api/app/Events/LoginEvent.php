<?php

namespace App\Events;

use App\Models\User;

class LoginEvent extends Event
{

    public $user;

    /**
     * Create a new event instance.
     * @param $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
