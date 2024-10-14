<?php

namespace Soszin\LaravelAuthentik\Events;


use Illuminate\Foundation\Events\Dispatchable;
use Soszin\LaravelAuthentik\AuthentikUser;

class UserAuthenitcated
{
    use Dispatchable;

    public function __construct(public AuthentikUser $user)
    {

    }
}
