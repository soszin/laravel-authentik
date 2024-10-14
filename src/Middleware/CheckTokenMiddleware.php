<?php

namespace Soszin\LaravelAuthentik\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Soszin\LaravelAuthentik\Facades\Authentik;

class CheckTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user) {
            $token = $user->deviceAuthentikToken()->first();
            if ($token) {
                $responseData = Authentik::introspectToken($token->access_token);

                if (!($responseData['active'] ?? false)) {

                    $refreshTokenData = Authentik::refreshToken($token->refresh_token);
                    if (!is_null($refreshTokenData)) {
                        $token->access_token = $refreshTokenData['access_token'];
                        $token->refresh_token = $refreshTokenData['refresh_token'];
                        $token->expires_at = now()->addSeconds($refreshTokenData['expires_in']);
                        $token->refresh_token_expires_at = now()->addSeconds(config('authentik.refresh_token_expires_at'));
                        $token->save();
                    } else {
                        Auth::logout();
                    }
                }
            }
        }

        return $next($request);
    }
}
