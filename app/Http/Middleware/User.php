<?php

namespace App\Http\Middleware;

use Closure;

class User
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
        if(empty(session('user'))){
            return redirect()->route('login.form')->with('error','Anda Belum Login!');
        }
        else{
            return $next($request);
        }
    }
}
