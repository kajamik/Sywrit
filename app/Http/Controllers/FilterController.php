<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Articoli;
use App\Models\Editori;
use App\Models\BackupAccountsImages;
use App\Models\BackupGroupsImages;

use Storage;
use Image;
use File;

class FilterController extends Controller
{
  public function postSettings(Request $request)
  {
    $this->validate($request,[
      'name' => 'required|string|min:3|max:12',
      'surname' => 'required|string|min:3|max:12',
      'birthdate' => 'required|date'
    ]);
    $query = User::find(\Auth::user()->id);
    $query->nome = $request->name;
    $query->cognome = $request->surname;
    $query->birthdate = $request->birthdate;
    if($a = $request->cover){
      $this->validate($request,[
        'cover' => 'image|mimes:jpeg,jpg,png,gif',
      ],[
        'cover.mimes'  => 'Il tipo del file non è valido',
      ]);
      $fileName = rand().'.jpg';
      $image = Image::make($a)->resize(1110, 350)->encode('jpg');
      Storage::disk('accounts')->put($fileName, $image);
      $query->copertina = $fileName;
    }
    if($a = $request->avatar){
      $this->validate($request,[
        'avatar' => 'image|mimes:jpeg,jpg,png,gif',
      ],[
        'avatar.mimes'  => 'Il tipo del file non è valido',
      ]);
      if($a->getClientOriginalExtension() == 'gif'){
        $fileName = rand().'.gif';
        // Insert gif animated
        File::copy($a->getRealPath(), public_path().'/storage/accounts/'.$fileName);
      }else{
        $fileName = rand().'.jpg';
        //crop(480,500,0,0)
        $image = Image::make($a)->resize(160, 160)->encode('jpg');
        Storage::disk('accounts')->put($fileName, $image);
      }
      $query->avatar = $fileName;
    }
    if($a = $request->old_password){
      $this->validate($request,[
        'old_password' => 'required|string|min:6',
        'password' => 'required|string|min:6|different:old_password|confirmed'
      ]);
      $new_password = bcrypt($request->password);
      $query->password = $new_password;
    }
    $query->save();
    return redirect('settings');
  }

  public function leaveGroup(Request $request)
  {
      $query = Editori::where('id',\Auth::user()->id_gruppo)->first();
      $slug = $query->slug;

      if($query->direttore == \Auth::user()->id)
        return redirect('group/'.$slug)->with(['type' => 'container_right__small', 'message' => 'Non puoi lasciare il gruppo se sei il direttore']);

      try{
        $collection = collect(explode(',',$query->componenti));
        $collection->splice($collection->search(\Auth::user()->id),1);
        $query->componenti = $collection->implode(',');
        $query->save();
        $user = User::find(\Auth::user()->id);
        $user->id_gruppo = null;
        $user->save();
      }catch(ErrorException $error){
        //
      }

      return redirect('group/'.$slug);
    }

    public function postEditoriaSettings($slug,Request $request)
    {
      $query = Editori::where('slug',$slug)->first();
      $query->nome = $request->name;
      if($a = $request->background){
        $this->validate($request,[
          'background' => 'image|mimes:jpeg,jpg,png,gif',
        ],[
          'background.mimes'  => 'Il tipo del file non è valido',
        ]);
        $resize = '__160x160'.Str::random(64).'.jpg';
        $normal_image = '__'.Str::random(64).'.jpg';
        $image = Image::make($a)->resize(160, 160)->encode('jpg');
        Storage::disk('groups')->put($resize, $image);
        $image = Image::make($a)->encode('jpg');
        Storage::disk('groups')->put($normal_image, $image);
        $query->background = $resize;
      }
      if($a = $request->logo){
        $this->validate($request,[
          'logo' => 'image|mimes:jpeg,jpg,png,gif',
        ],[
          'logo.mimes'  => 'Il tipo del file non è valido',
        ]);
        $resize = '__160x160'.Str::random(64).'.jpg';
        $normal_image = '__'.Str::random(64).'.jpg';
        $image = Image::make($a)->resize(160, 160)->encode('jpg');
        Storage::disk('groups')->put($resize, $image);
        $image = Image::make($a)->encode('jpg');
        Storage::disk('groups')->put($normal_image, $image);
        $query->logo = $resize;
      }
      $query->save();

      if($a){
        // Backup dell'immage non ridimensionata
        $backup = new BackupGroupsImages();
        $backup->img_title = $normal_image;
        $backup->group_id = $query->id;
        $backup->save();
      }
      return redirect()->back();
    }
}
