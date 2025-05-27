<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserActivation
{
    
    public function handle(Request $request, Closure $next)
    {
        if(auth()->guard('api')->user()->is_activate == 1 && is_null(auth()->guard('api')->user()->deleted_at) && !is_null(auth()->guard('api')->user()->mobile_verified_at)){
            return $next($request);
        }else{
            return responseJson(401, "This Account Not Activate , Please Contact Technical Support");
        }
    }
}
