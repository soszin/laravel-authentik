<?php

namespace Soszin\LaravelAuthentik;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Soszin\LaravelAuthentik\Models\UserToken;

class UserService
{
    public function saveUserToken(User $user, AuthentikUser $authentikUser): false|Model
    {
        $token = new UserToken([
            'access_token' => $authentikUser->getAccessToken(),
            'token_type' => 'Bearer',
            'expires_at' => now()->addSeconds($authentikUser->getExpiresIn()),
            'refresh_token' => $authentikUser->getRefreshToken(),
            'refresh_token_expires_at' => now()->addSeconds(config('authentik.refresh_token_expires_at')),
        ]);
        $token->device_id = request()->cookie('device_id');
        $token->session_id = session()->getId();

        return $user->createAuthentikToken($token);
    }

    public function deleteUserTokenBySession(string $sessionId): bool|null
    {
        $token = UserToken::where('session_id', $sessionId);
        return $token->delete();
    }
}
