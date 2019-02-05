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
      $query = Articoli::where('id',$request->slug)->first();
      if (!$query->status && Auth::user()){
        if((!$query->id_gruppo && Auth::user()->id == $query->autore) ||
          ($query->id_gruppo && Auth::user()->id_gruppo == $query->id_gruppo))
          return $next($request);
      }
      elseif($query->status)
          return $next($request);

      return abort(404);
    }
}
