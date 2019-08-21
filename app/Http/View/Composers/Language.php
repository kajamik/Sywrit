<?php


namespace App\Http\View\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class Language {
    const SESSION_KEY = 'locale';
    const LOCALES = ['it', 'en', 'fr'];
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
      if (auth()->user()) {
        $lang = explode('_', auth()->user()->language)[0];
        if (in_array($lang, self::LOCALES)) {
          session()->put(self::SESSION_KEY, $lang);
        } else {
          session()->put(self::SESSION_KEY, 'en');
        }
      } elseif(!session()->has(self::SESSION_KEY)) {
          session()->put(self::SESSION_KEY, request()->getPreferredLanguage(self::LOCALES));
      }
      app()->setLocale(session()->get(self::SESSION_KEY));
    }


}
