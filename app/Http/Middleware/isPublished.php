<?php

namespace App\Http\Middleware;

use App\Models\Articoli;
use Closure;
use Auth;

class isPublished
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
      $query = Articoli::where('slug',$request->slug)->first();
        if($query->status || ( Auth::user() && (Auth::user()->id == Auth::user()->autore || Auth::user()->id_gruppo == $query->id_gruppo))){
          return $next($request);
        }
        return abort(404);
    }
}
