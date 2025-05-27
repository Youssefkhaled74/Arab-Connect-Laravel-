<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminLogin
{
    
    public function handle(Request $request, Closure $next)
    {
        if(auth()->guard('admin')->check()){
            if(auth()->guard('admin')->user()->is_activate == 1){
                return $next($request);
            }else{
                flash()->error("This Account Not Activate , Please Contact Technical Support");
                return redirect(route('admin/login'));
            }
        }else{
            flash()->error("FORBIDDEN , Please Contact Technical Support");
            return redirect(route('admin/login'));
        }
    }
}
