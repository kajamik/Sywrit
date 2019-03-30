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
        \Carbon\Carbon::setUTF8(true);
        setLocale(LC_TIME, config('app.locale'));
        \Schema::defaultStringLength(191);
        Validator::resolver(function($translator, $data, $rules, $messages) {
          return new HashValidator($translator, $data, $rules, $messages);
        });

        View::composer(
            'profile', 'App\Http\View\Composers\ProfileComposer'
        );
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
