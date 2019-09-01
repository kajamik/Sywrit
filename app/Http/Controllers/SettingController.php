<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Validator;
use Response;
use Auth;
use SEOMeta;

use App\Models\User;
use App\Models\SocialService;
use App\Models\UserLinks;

class SettingController extends Controller
{
    public $SETTINGS = 'front.pages.profile.settings';

    public function __construct()
    {
          SEOMeta::setDescription(trans('label.meta.web_description', ['name' => config('app.name')]))
                    ->setCanonical(\Request::url());
    }
    public function index()
    {
        SEOMeta::setTitle(trans('label.title.settings'). ' - Sywrit', false);
        return view($this->SETTINGS);
    }

    public function getAccount()
    {
        SEOMeta::setTitle(trans('label.title.edit_profile'). ' - Sywrit', false);
        return view($this->SETTINGS)->with('slug', 'account');
    }

    public function getAccountName()
    {
        SEOMeta::setTitle(trans('label.title.edit_info'). ' - Sywrit', false);
        $apps = SocialService::orderBy('name', 'asc')->get();
        $my_apps = UserLinks::where('user_id', Auth::user()->id)->get();
        return view($this->SETTINGS, compact('apps','my_apps'))->with(['slug' => 'account', 'slug2' => 'name']);
    }

    /*public function getAccountUsername()
    {
        return view($this->SETTINGS)->with(['slug' => 'account', 'slug2' => 'username']);
    }*/

    /*public function getAccountManage()
    {
        SEOMeta::setTitle(trans('label.title.account_managment'). ' - Sywrit', false);
        return view($this->SETTINGS)->with(['slug' => 'account', 'slug2' => 'manage']);
    }*/

    public function getChangePassword()
    {
        SEOMeta::setTitle(trans('label.title.password'). ' - Sywrit', false);

        if(empty(Auth::user()->social_auth_id)) {
          return view($this->SETTINGS)->with('slug', 'change_password');
        } else {
          abort(404);
        }
    }

    public function getChangeLanguage()
    {
        SEOMeta::setTitle(trans('label.title.language'). ' - Sywrit', false);

        $lang = config('lang.locales');
        return view($this->SETTINGS, compact('lang'))->with('slug', 'change_language');
    }

    // POST DATA

    public function postAccountName(Request $request)
    {
        $this->validate($request,[
          'name' => 'required|string|min:3|max:12',
          'surname' => 'required|string|min:3|max:12'
        ]);
        $user = User::find(\Auth::user()->id);
        $user->name = $request->name;
        $user->surname = $request->surname;
        if($a = $request->cover){
          $this->validate($request,[
            'cover' => 'image|mimes:jpeg,jpg,png,gif',
          ],[
            'cover.image' => 'Devi inserire un\'immagine',
            'cover.mimes'  => 'Formato immagine non valido',
          ]);

          deleteFile( public_path('sf/aa/'.Auth::user()->copertina) );

          $fileName = rand().Str::random(14).'.jpg';

          uploadFile($a, array(
            'name' => $fileName,
            'path' => public_path('sf/aa/'),
            'width' => '1100',
            'height' => '350',
            'mimetype' => 'jpg',
            'quality' => '100'
          ));

          /*$fileName = 'ac-'.rand().'.jpg';
          $path = public_path('sf/accounts/'. $fileName);
          $image = Image::make($a)->resize(1110, 350)->encode('jpg', 100)
                                  ->save( $path );*/

          $user->copertina = asset('sf/aa/'. $fileName);
        }
        if($a = $request->avatar){
          $this->validate($request,[
            'avatar' => 'image|mimes:jpeg,jpg,png,gif',
          ],[
            'avatar.image' => 'Devi inserire un\'immagine',
            'avatar.mimes'  => 'Formato immagine non valido',
          ]);

          deleteFile( public_path('sf/aa/'.Auth::user()->avatar) );

          if($a->getClientOriginalExtension() == 'gif'){
            $fileName = 'ac-'.rand().'.gif';
            // Insert gif animated
            File::copy($a->getRealPath(), public_path().'/sf/aa/'. $fileName);
          }else{

            $fileName = rand().Str::random(14).'.jpg';

            uploadFile($a, array(
              'name' => $fileName,
              'path' => public_path('sf/aa/'),
              'width' => '160',
              'height' => '160',
              'mimetype' => 'jpg',
              'quality' => '100'
            ));

            /*$fileName = 'ac-'.rand().'.jpg';
            $path = public_path('sf/accounts/'. $fileName);
            //crop($request->width[0],$request->height[0],$request->x[0],$request->y[0])
            $image = Image::make($a)->resize(160, 160)->encode('jpg', 100)
                            ->save( $path );*/
          }
          $user->avatar = asset('sf/aa/'. $fileName);
        }
        // Socials
        if(!empty($request->bio)) {
          $user->biography = $request->bio;
        }
        if(!empty($request->social_account_name)) {
          $contact = collect();
          foreach($request->social_account_name as $key => $value) {
            if(!empty($value)) {
              $contact->push($key);
              $service = SocialService::find($request->social_service_name[$key]);
              if($service->count()) {
                $query = UserLinks::where('user_id', Auth::user()->id)->find($key);
                $query->service_id = $service->id;
                $query->url = $value;
                $query->save();
              }
            }
          }
          // elimino il resto dei miei contatti
          if($contact->isNotEmpty()) {
            UserLinks::where('user_id', Auth::user()->id)->whereNotIn('id', $contact)->delete();
          }
        } else {
          UserLinks::where('user_id', Auth::user()->id)->delete();
        }
        if(!empty($request->add_social_account_name)) {
          foreach($request->add_social_account_name as $key => $value) {
            if(!empty($value)) {
              $service = SocialService::find($request->add_social_service_name[$key]);
              if($service->count()) {
                $query = new UserLinks();
                $query->user_id = Auth::user()->id;
                $query->service_id = $service->id;
                $query->url = $value;
                $query->save();
              }
            }
          }
        }

        $user->save();
        return redirect()->back()->with('successful_changes', true);
    }

    /*public function postAccountUsername(Request $request)
    {
        $query = User::find(Auth::user()->id);
        $input = [
          'username' => 'required|min:4|unique:users,slug',
        ];
        $alert = [
          'username.required' => 'Nome utente richiesto',
        ];
        if(empty($query->social_auth_id)) { //10213791307632989
          array_push($input, ['password_confirmation' => 'required|min:6|hash:'.$query->password]);
        }
        $this->validate($request, [
        'username' => 'required|min:4|unique:users,slug',
      ]);
    }*/

    public function postChangePassword(Request $request)
    {
        $query = User::find(Auth::user()->id);
        if(empty($query->social_auth_id)) {
          $validation = Validator::make($request->all(),[
            'old_password' => 'required|min:6|hash:'.$query->password,
            'password' => 'required|min:6|different:old_password',
            'password_confirmation' => 'required|min:6|same:password'
          ],[
            'old_password.required' => 'La password è richiesta',
            'old_password.min' => 'La password deve essere lunga almeno 6 caratteri',
            'old_password.hash' => 'La password non è valida',
            'password.required' => 'La password è richiesta',
            'password_confirmation.required' => '',
            'password.min' => 'La password deve essere lunga almeno 6 caratteri',
            'password.different' => 'La nuova password non è stata cambiata',
          ]);

          if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
          }
          $query->password = bcrypt($request->password);
          $query->save();
        }
        return redirect()->back();
    }

    public function postChangeLanguage(Request $request)
    {
        $lang = $request->lang;
        $iso = config('lang.locales.'. $lang);
        $query = User::find(Auth::user()->id);
        $query->language = $iso;
        $query->save();
        return redirect()->back();
    }
}
