<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class isSuspended
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
      if(Auth::user() && Auth::user()->suspended) {
        return response()->view('errors.401', [], 401);
      }
      return $next($request);
    }
}
