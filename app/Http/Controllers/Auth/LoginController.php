<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \Illuminate\Http\Request;

// SEO
use SEOMeta;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        SEOMeta::setTitle('Accedi - Sywrit');

        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
      $del = \App\Models\AccountDeletionRequest::where('user_id', $user->id);
      if($del->count()) {
        $del = $del->first();
        $user = \App\Models\User::find($user->id);
        $user->cron = '0';
        $user->save();
        $del->delete();
      }
    }
}
