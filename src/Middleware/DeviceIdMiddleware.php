<?php

namespace Soszin\LaravelAuthentik\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class DeviceIdMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if(!request()->cookie('device_id')) {
            $deviceId = Str::uuid();

            Cookie::queue('device_id', $deviceId, 60 * 24 * 365);
        }

        return $next($request);
    }
}
