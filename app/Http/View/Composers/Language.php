<?php

namespace App\Http\View\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Language {
    const SESSION_KEY = 'locale';
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
      if(auth()->user()) {
        $lang = auth()->user()->language;
        if(!empty($lang) && in_array($lang, config('lang.locales'))) {
          session()->put(self::SESSION_KEY, $lang);
        } else {
          $preferred = request()->getPreferredLanguage(config('lang.locales'));
          session()->put(self::SESSION_KEY, $preferred);
          $query = DB::table('utenti')->where('id', auth()->user()->id)
                      ->update(['language' => $preferred]);
        }
      } elseif(!session()->has(self::SESSION_KEY)) {
          session()->put(self::SESSION_KEY, request()->getPreferredLanguage(config('lang.locales')));
      }
      app()->setLocale(explode(config('lang.trans.split'), session(config('lang.trans.session.name')))[0]);
    }


}
