<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Validator;
use App\Http\Validators\HashValidator;

use View;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Debugbar::disable();

        \Carbon\Carbon::setUTF8(true);
        setlocale(LC_TIME, 'it', 'it_IT', 'italian');
        \Carbon\Carbon::setLocale('it');
        \Schema::defaultStringLength(191);
        Validator::resolver(function($translator, $data, $rules, $messages) {
          return new HashValidator($translator, $data, $rules, $messages);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
