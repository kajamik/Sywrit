<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Articoli;
use App\Models\Editori;

use Storage;
use Image;

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
      $fileName = rand().'.jpg';
      $image = Image::make($a)->resize(1110, 350)->encode('jpg');
      Storage::disk('accounts')->put($fileName, $image);
      $query->copertina = $fileName;
    }
    if($a = $request->avatar){
      $fileName = rand().'.jpg';
      $image = Image::make($a)->crop(480,500,0,0)->resize(160, 160)->encode('jpg');
      Storage::disk('accounts')->put($fileName, $image);
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
}
