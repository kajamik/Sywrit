<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \Illuminate\Http\Request;

use App\Models\User;
use Auth;
use Socialite;

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

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $userSocial = Socialite::driver('facebook')
                          ->fields([
                            'name',
                            'first_name',
                            'last_name',
                            'email',
                            'gender',
                            'verified'
                          ])
                          ->user();

        $user = User::where('social_auth_id', $userSocial->getId())->first();

        if(!$user) {
          // New User
          $user = User::create([
              'name' => $userSocial->user['first_name'],
              'surname' => $userSocial->user['last_name'],
              'email' => $userSocial->user['email'],
              'password' => $userSocial->token,
              'avatar' => $userSocial->avatar,
              'social_auth_id' => $userSocial->getId(),
              // informazioni aggiuntive
              'rank' => '1',
              'points' => '0',
              'followers_count' => '0',
              'notifications_count' => '0',
          ]);

          $user->slug = $user->id.'-'.str_slug($userSocial->user['first_name'].$userSocial->user['last_name'], '');
          $user->save();

          // invio l'email di benvenuto all'utente
          $user->notify(new \App\Notifications\UserWelcome($user->name));
        }

        Auth::login($user, true);

        return redirect($this->redirectTo);

    }
}
