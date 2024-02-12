<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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
        if($request->user()->role=='admin'){
            return $next($request);
        } else if($request->user()->role=='pedagang'){
            return $next($request);
        }else if($request->user()->role=='nelayan'){
            return $next($request);
        }
        else{
            request()->session()->flash('error','Anda tidak memiliki izin untuk mengakses halaman ini!');
            return redirect()->route($request->user()->role);
        }
    }
}
