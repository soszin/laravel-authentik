<?php

namespace Soszin\LaravelAuthentik;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Authentik\Provider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class LaravelAuthentikServiceProvider extends ServiceProvider
{
    private UserService $userService;
    public function __construct($app)
    {
        parent::__construct($app);
        $this->userService = app(UserService::class);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/authentik.php', 'authentik'
        );

        $this->app->singleton('authentik', function ($app) {
            return new AuthentikClient();
        });
    }

    public function boot(): void
    {
        $this->registerResources();
        $this->bindEvents();
    }

    private function registerResources(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'authentik');
        Blade::componentNamespace('Soszin\\LaravelAuthentik\\View\\Components', 'authentik');
    }

    private function bindEvents(): void
    {
        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('authentik', Provider::class);
        });

        Event::listen(Login::class, function ($event) {
            $user = auth()->user();
            $deviceId = request()->cookie('device_id');

            if ($user && $deviceId) {
                $deviceToken = $user->deviceAuthentikToken()->first();
                if ($deviceToken) {
                    $deviceToken->session_id = session()->getId();
                    $deviceToken->save();
                }
            }
        });

        Event::listen(Logout::class, function ($event) {
            $this->userService->deleteUserTokenBySession(session()->getId());
        });
    }
}
