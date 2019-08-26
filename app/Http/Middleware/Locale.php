<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;

class Locale {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        App::setLocale(explode(config('lang.trans.split'), session(config('lang.trans.session.name')))[0]);
        return $next($request);
    }


}
