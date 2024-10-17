# Laravel Authentik

---

### Add the repository to the composer.json file
```    
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/soszin/laravel-authentik"
    }
]
```
### Install laravel-authentik into your project
    composer require soszin/laravel-authentik

### Add the configuration to the config file
```php
// config/services.php
    ...
    'authentik' => [
        'base_url' => env('AUTHENTIK_BASE_URL'),
        'client_id' => env('AUTHENTIK_CLIENT_ID'),
        'client_secret' => env('AUTHENTIK_CLIENT_SECRET'),
        'redirect' => env('AUTHENTIK_REDIRECT_URI')
    ],
```

### Add the middleware

```php
// app/Http/Kernel.php

protected $middlewareGroups = [
    'web' => [
        ...
        DeviceIdMiddleware::class,
    ],
    ...
];

protected middlewareAliases = [
    'authentik.token' => CheckTokenMiddleware::class,
]
```

### Add token verification for all routes requiring authorization
```php
Route::middleware(['web', 'authentik.token'])->get('secret-page', [SecretController::class, 'secretAction'])
```

### Add the login button via SSO
```php
use Soszin\LaravelAuthentik\IntegrateWithAuthentik;

class User extends Authenticatable {
    use IntegrateWithAuthentik;
}
```

### Run the migrations
```shell
php artisan migrate
```

### Add the login button via SSO
```bladehtml
<x-authentik::log-in-by-authentik />
```
You can also use a custom button, e.g.:
```bladehtml
<a href="{{route('authentik.redirect')}}" class="custom">
    Log in via SSO
</a>
```

### Add a listener to handle the user authenticated by Authentik
```php
// app/Listeners/CreateUserFromAuthentikListener.php

use Soszin\LaravelAuthentik\UserService;
use Soszin\LaravelAuthentik\Events\UserAuthenitcated;

readonly class CreateUserFromAuthentikListener {
    public function __construct(
        private UserService $userService
    ) {}
    
    public function handle(UserAuthenitcated $event) 
    {
        $authentikUser = $event->user;
        
        $userQueryBuilder = User::whereEmail($authentikUser->getEmail());
        
        if ($userQueryBuilder->count() > 0) {
            $user = $userQueryBuilder->first();
        } else {
            // 1. Create the user
            User::create([
                'name' => $authentikUser->getFirstName(),
                'surname' => $authentikUser->getLastName(),
                'email' => $authentikUser->getEmail()            
            ]);
            $user->markEmailAsVerified();
        }
        // 2. Log in the user
        Auth::login($user, true);
        // 3. Save the token
        $this->authentikUserService->saveUserToken($user, $authentikUser);
    }
}
```

### Register the listener to listen for the UserAuthenticated event

```php
// app/Providers/EventServiceProvider.php
protected $listen = [
    Registered::class => [
        SendEmailVerificationNotification::class,
    ],
    UserAuthenitcated::class => [
        CreateUserFromAuthentikListener::class
    ]
];
```
