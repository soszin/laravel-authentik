<?php

namespace Soszin\LaravelAuthentik\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Laravel\Socialite\Facades\Socialite;
use Soszin\LaravelAuthentik\AuthentikUser;
use Soszin\LaravelAuthentik\Events\UserAuthenitcated;
use Soszin\LaravelAuthentik\Events\UserAuthenticationError;

class AuthentikController extends BaseController
{
    public function redirectToProvider()
    {

        return Socialite::driver('authentik')
            ->scopes(['openid goauthentik.io/api email openid profile offline_access'])
            ->redirect();
    }

    public function callback()
    {
        $user = Socialite::driver('authentik')->user();

        if ($user) {
            $authentikUser = new AuthentikUser();
            $authentikUser->setFullName($user->getName())
                ->setEmail($user->getEmail())
                ->setAccessToken($user->token)
                ->setRefreshToken($user->refreshToken)
                ->setExpiresIn($user->expiresIn)
                ->setGroups($user->offsetGet('groups'));

            event(new UserAuthenitcated($authentikUser));
        } else {
            UserAuthenticationError::dispatch();
            return redirect(config('authentik.error_redirect'));
        }

        return redirect(config('authentik.success_redirect'));
    }
}
