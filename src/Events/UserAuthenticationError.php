<?php

namespace Soszin\LaravelAuthentik\Events;

use Illuminate\Foundation\Events\Dispatchable;

class UserAuthenticationError
{
    use Dispatchable;

    public function __construct()
    {

    }
}
