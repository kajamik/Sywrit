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
