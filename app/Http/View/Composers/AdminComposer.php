<?php


namespace App\Http\View\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AdminComposer {
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
      if(Auth::check() && Auth::user()->permission >= 3) {
        \Debugbar::enable();
      } else {
        //\Debugbar::disable();
      }
    }


}
