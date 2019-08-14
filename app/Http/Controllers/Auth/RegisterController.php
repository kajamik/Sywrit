<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use \Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
// Notification
use App\Notifications\UserWelcome as UserWelcomeNotification;
//
use App\Models\User;

use Auth;
use Image;
use Storage;

// Seo
use SEOMeta;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        SEOMeta::setTitle('Iscriviti - Sywrit');

        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:utenti'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*public function register(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if($user) {
          return redirect('recover/code?email='. urlencode($user->email));
        } else {
          $this->validator($request->all())->validate();

          event(new Registered($user = $this->create($request->all())));

          $this->guard()->login($user);

          return $this->registered($request, $user)
                          ?: redirect($this->redirectPath());
        }
    }*/

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
      $redirectTo = \Session::get('redirectTo');
      \Session::pull('redirectTo');

      $img = Image::make(Image::canvas(160, 160, 'rgba(199, 191, 230,1)'));
      $img->text(Str::limit($data['name'], 1, '').Str::limit($data['surname'], 1, ''), 80, 48, function($font) {
        $font->file(public_path('fonts/RobotoSlab-Regular.ttf'));
        $font->size(80);
        $font->color('#000');
        $font->align('center');
        $font->valign('top');
        $font->angle(0);
      });
      $img_name = '_160x160'.Str::random(64).'.jpg';
      $img->save(public_path('sf/aa/'. $img_name));

        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar' => asset('sf/aa/'. $img_name),
            'verified' => '0',
            // informazioni aggiuntive
            'rank' => '1',
            'points' => '0',
            'followers_count' => '0',
            'notifications_count' => '0',
        ]);

        $user->slug = $user->id.'-'.str_slug($data['name'].$data['surname'], '');
        $user->save();

        // invio l'email di benvenuto all'utente
        //$user->notify(new UserWelcomeNotification($user->name));

        if($redirectTo) {
          $this->redirectTo = $redirectTo;
        }

        return $user;
    }
}
