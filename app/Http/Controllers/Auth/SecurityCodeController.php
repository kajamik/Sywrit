<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \Illuminate\Http\Request;
use App\Notifications\SecurityCode;

use Validator;

use App\Models\User;
use App\Models\SecurityCode as sAuth;
use Auth;
use Socialite;

// SEO
use SEOMeta;

class SecurityCodeController extends Controller
{

    public function __construct()
    {
        SEOMeta::setTitle('Codice di sicurezza - Sywrit');
    }

    public function getCheckCode(Request $request)
    {
        if($request->email) {
          $email = $request->email;
          $token = strtoupper(str_random(6));
          if(($oldToken = sAuth::where('email', $email))->first()) {
            $oldToken->delete();
          }
          $code = new sAuth();
          $code->email = $email;
          $code->token = $token;
          $code->save();
          //
          $user = User::where('email', $email)->first();
          // invio l'email di benvenuto all'utente
          //$user->notify(new SecurityCode($user, $token));
          return view('auth.code', compact('email'));
        } else {
          return redirect('/');
        }
    }

    public function postCheckCode(Request $request)
    {
        $this->validate($request,[
          'sCode' => 'required|same:token',
        ],[
          'sCode.required' => 'Il codice di sicurezza è obbligatorio',
          'sCode.same' => 'Il codice di sicurezza non è corretto'
        ]);

        $redirectTo = \Session::get('redirectTo') ? \Session::get('redirectTo') : '/';
        $token = $request->security_code;
        if(($oldToken = sAuth::where('email', $request->email))->first()) {
          if($oldToken->first()->token == $token) {
            $user = User::where('email', $oldToken->email)->first();
            $oldToken->delete();
            Auth::login($user, true);
            return redirect($redirectTo);
          }
        }
    }
}
