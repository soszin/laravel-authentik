<?php

namespace Soszin\LaravelAuthentik;

use Soszin\LaravelAuthentik\Models\UserToken;

trait IntegrateWithAuthentik
{
    public function authentikTokens()
    {
        return $this->hasMany(config('authentik.user_token_model'));
    }

    public function sessionAuthentikToken()
    {
        return $this->hasMany(config('authentik.user_token_model'))->where('session_id', session()->getId());
    }

    public function deviceAuthentikToken()
    {
        return $this->hasMany(config('authentik.user_token_model'))->where('device_id', request()->cookie('device_id'));
    }

    public function createAuthentikToken(UserToken $accessToken): false|UserToken
    {
        return $this->authentikTokens()->save($accessToken);
    }
}
