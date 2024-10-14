<?php

declare(strict_types=1);

return [
    'table_name' => 'user_tokens',
    'user_model' => \App\Models\User::class,
    'user_token_model' => \Soszin\LaravelAuthentik\Models\UserToken::class,
    'success_redirect' => '/',
    'error_redirect' => '/',
    'refresh_token_expires_at' => 864000,
];
