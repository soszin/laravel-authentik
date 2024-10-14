<?php

namespace Soszin\LaravelAuthentik\Models;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    public function __construct(array $attributes = [])
    {
        $this->table = config('authentik.table_name');
        parent::__construct($attributes);
    }

    protected $fillable = [
        'access_token',
        'refresh_token',
        'token_type',
        'scopes',
        'expires_at',
        'refresh_token_expires_at',
    ];

    protected $dates = [
        'expires_at',
        'refresh_token_expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(config('authentik.user_model'));
    }
}
