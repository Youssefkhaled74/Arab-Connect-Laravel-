<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserActivation
{

    public function handle(Request $request, Closure $next)
    {
        $user = auth()->guard('api')->user();

        if ($user->is_activate != 1 || !is_null($user->deleted_at)) {
            return responseJson(401, "This Account Not Activate , Please Contact Technical Support");
        }

        if (is_null($user->email_verified_at)) {
            return responseJson(401, "Please verify your email");
        }

        return $next($request);
    }
}
