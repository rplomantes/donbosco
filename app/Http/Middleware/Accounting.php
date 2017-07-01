<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Accounting
{

    public function handle($request, Closure $next)
    {
        if(Auth::user()->accesslevel == env('USER_ACCOUNTING') || Auth::user()->accesslevel == env('USER_ACCOUNTING_HEAD') || Auth::user()->accesslevel == env('USER_ECONOMIC_ADMIN')){
          
            return $next($request);
        }
        
        return redirect('/');  
    }
}
