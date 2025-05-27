<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class LimitReq
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $executed = RateLimiter::attempt('send-message:', 100, function() {});
        if(! $executed){
            abort(429, 'Too Many Requests');
        }
        return $next($request);
    }
}
