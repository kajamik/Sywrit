<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Debugbar;

class TestMiddleware
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
         if(auth()->user()) {
             Debugbar::enable();
         } else {
             Debugbar::disable();
         }

         return $next($request);
     }

}
