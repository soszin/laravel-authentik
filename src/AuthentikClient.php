<?php

namespace Soszin\LaravelAuthentik;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class AuthentikClient
{
    protected PendingRequest $httpClient;
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $redirectUri;
    protected string $scope;

    public function __construct()
    {
        $this->baseUrl = config('services.authentik.base_url');
        $this->httpClient = Http::baseUrl($this->baseUrl);
        $this->clientId = config('services.authentik.client_id');
        $this->clientSecret = config('services.authentik.client_secret');
        $this->redirectUri = config('services.authentik.redirect');
        $this->scope = config('services.authentik.scope', 'openid profile email');
    }

    public function introspectToken($token): array
    {
        $response = $this->httpClient->asForm()
            ->withBasicAuth($this->clientId, $this->clientSecret)
            ->post('/application/o/introspect/', [
                'token' => $token
            ]);

        return $response->json();
    }

    public function getUserInfo($token): null|array
    {
        $response = $this->httpClient->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/application/o/userinfo/');

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }

    public function refreshToken(string $refreshToken): null|array
    {
        $response = $this->httpClient->asForm()
            ->post('/application/o/token/', [
                'grant_type' => 'refresh_token',
                'client_id' => config('services.authentik.client_id'),
                'client_secret' => config('services.authentik.client_secret'),
                'refresh_token' => $refreshToken,
            ]);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }
}
