<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use Auth;
use Socialite;
use Image;

class SocialController extends Controller
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

  private $redirectTo;

  /**
   * Redirect the user to the GitHub authentication page.
   *
   * @return \Illuminate\Http\Response
   */
  public function redirectToProvider(Request $request)
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
      $this->redirectTo = \Session::get('redirectTo') ? \Session::get('redirectTo') : '/';

      \Session::pull('redirectTo');

      $userSocial = Socialite::driver('facebook')
                        ->fields([
                          'name',
                          'first_name',
                          'last_name',
                          'email',
                          'gender',
                        ])
                        ->user();

      return $this->socialAuthenticate($userSocial);
  }

  public function socialAuthenticate($social_user)
  {
      $user = User::where('email', $social_user->email)->first();

      if($user) {
        if($social_user->getId() != $user->social_auth_id) {
          return redirect($this->redirectTo);
        }
      } else {
        $social_user->avatar = str_replace('?type=normal', '?type=large', $social_user->avatar);

        $fileName = rand().Str::random(14).'.jpg';

        Image::make($social_user->avatar)->fit(160, 160)->save(public_path('sf/aa/'. $fileName));

        $user = User::create([
            'name' => $social_user->user['first_name'],
            'surname' => $social_user->user['last_name'],
            'email' => $social_user->user['email'],
            'password' => $social_user->token,
            'avatar' => asset('sf/aa/'. $fileName),
            'social_auth_id' => $social_user->getId(),
            'verified' => '0',
            // informazioni aggiuntive
            'rank' => '1',
            'points' => '0',
            'followers_count' => '0',
            'notifications_count' => '0',
            'language' => session()->get('locale'),
        ]);

        $user->slug = $user->id.'-'.str_slug($social_user->user['first_name'].$social_user->user['last_name'], '');
        $user->save();

        // invio l'email di benvenuto all'utente
        //$user->notify(new \App\Notifications\UserWelcome($user->name));
      }

      Auth::login($user, true);

      return redirect($this->redirectTo);
  }

}
