<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserMaker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if(Auth::user()->idno == 'rplomantes' |Auth::user()->idno == '12345'| Auth::user()->idno == 'larabelle'){
          
         return $next($request);
        }
        
        return redirect('/');  
        
    }
}
