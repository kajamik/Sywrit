<?php

namespace App\Http\View\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Language
{
  /**
   * Bind data to the view.
   *
   * @param  View $view
   * @return void
   */
  public function compose(View $view)
  {
      /*if(auth()->user()) {
        $lang = auth()->user()->language;
        if(!empty($lang) && in_array($lang, config('lang.locales'))) {
          session()->put(config('lang.trans.session.name'), $lang);
        } else {
          $preferred = request()->getPreferredLanguage(config('lang.locales'));
          session()->put(config('lang.trans.session.name'), $preferred);
          $query = DB::table('users')->where('id', auth()->user()->id)
                      ->update(['language' => $preferred]);
        }
      } elseif(!session()->has(config('lang.trans.session.name'))) {
          session()->put(config('lang.trans.session.name'), 'it_IT' );
      }*/ //session(config('lang.trans.session.name')
      //app()->setLocale(explode(config('lang.trans.split'), session(config('lang.trans.session.name')))[0]);
      app()->setLocale('it');
  }


}
