<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Articoli;
use App\Models\Editori;
use App\Models\BackupAccountsImages;
use App\Models\BackupGroupsImages;
use App\Models\InviteMessage;

use Storage;
use Image;
use File;
use Auth;

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
      $filePath = public_path().'/storage/accounts/'.Auth::user()->copertina;
      if(File::exists($filePath)){
        File::delete($filePath);
      }
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
      $filePath = public_path().'/storage/accounts/'.Auth::user()->avatar;
      if(File::exists($filePath)){
        File::delete($filePath);
      }
      if($a->getClientOriginalExtension() == 'gif'){
        $fileName = rand().'.gif';
        // Insert gif animated
        File::copy($a->getRealPath(), public_path().'/storage/accounts/'.$fileName);
      }else{
        $fileName = rand().'.jpg';
        //crop(480,500,0,0)
        $image = Image::make($a)->crop($request->width[0],$request->height[0],$request->x[0],$request->y[0])->resize(160, 160)->encode('jpg');
        Storage::disk('accounts')->put($fileName, $image);
      }
      $query->avatar = $fileName;
    }
    $query->save();
    return redirect('settings');
  }

  public function postChangeUsername(Request $request)
  {
    $this->validate($request,[
      'slug' => 'required|unique:utenti|regex:/(^[A-Za-z0-9 ]+$)+/',
      'verification' => 'required'
    ],[
      'slug.required' => 'Nome utente richiesto',
      'slug.unique' => ':username esiste già',
      'slug.regex' => 'Caratteri non consentiti',
      'verification.required' => 'Password necessaria'
    ]);

    $query = User::find(\Auth::user()->id);
    $query->slug = Str::slug($request->slug,'-');
    $query->save();
    return redirect('settings');
  }

  public function postChangePassword(Request $request)
  {
    $this->validate($request,[
      'old_password' => 'required|min:6',
      'password' => 'required|min:6|different:old_password'
    ],[
      'old_password.required' => 'La password è richiesta',
      'old_password.min' => 'La password deve essere lunga almeno 6 caratteri',
      'password.required' => 'La password è richiesta',
      'password.min' => 'La password deve essere lunga almeno 6 caratteri',
      'password.different' => 'La nuova password non dev\'essere uguale alla precedente',
      'password.confirmed' => 'Password non confermata'
    ]);
    $query = User::find(\Auth::user()->id);
    $query->password = bcrypt($request->password);
    $query->save();
    return redirect('settings');
  }

  public function inviteGroup(Request $request)
  {
    $query = User::find($request->_rq_token);
    if(Auth::user()->isDirector() && !$query->haveGroup()){
      $message = new InviteMessage();
      $message->sender_id = Auth::user()->id;
      $message->target_id = $query->id;
      $message->text = 'Sei stato invitato a collaborare con la redazione :name';
      $message->save();
    }
    return redirect($query->slug)->with(['type' => 'container_right__small', 'message' => 'Hai inviato la richiesta di collaborazione']);
  }

  public function leaveGroup(Request $request)
  {
      $query = Editori::where('id',\Auth::user()->id_gruppo)->first();
      $slug = $query->slug;

      if($query->direttore == \Auth::user()->id)
        return redirect($slug)->with(['type' => 'container_right__small', 'message' => 'Non puoi lasciare il gruppo se sei il direttore']);

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

      return redirect($slug);
    }

    public function deleteGroup()
    {
      $query = Editori::find(Auth::user()->id_gruppo);
      if($query->direttore == Auth::user()->id){
          if(!$query->accesso)
            $query->accesso = '1';
          else
            $query->accesso = '0';
          $query->save();
      }
      return redirect($query->slug);
    }

    public function postPublisherSettings($slug,Request $request)
    {
      $query = Editori::where('slug',$slug)->first();
      $query->nome = $request->name;
      if($a = $request->background){
        $this->validate($request,[
          'background' => 'image|mimes:jpeg,jpg,png,gif',
        ],[
          'background.mimes'  => 'Il tipo del file non è valido',
        ]);
        $filePath = public_path().'/storage/groups/'.$query->background;
        if(File::exists($filePath)){
          File::delete($filePath);
        }
        $resize = '__160x160'.Str::random(64).'.jpg';
        //$normal_image = '__'.Str::random(64).'.jpg';
        $image = Image::make($a)->resize(1110, 350)->encode('jpg');
        Storage::disk('groups')->put($resize, $image);
        //$image = Image::make($a)->encode('jpg');
        //Storage::disk('groups')->put($normal_image, $image);
        $query->background = $resize;
      }
      if($a = $request->logo){
        $this->validate($request,[
          'logo' => 'image|mimes:jpeg,jpg,png,gif',
        ],[
          'logo.mimes'  => 'Il tipo del file non è valido',
        ]);
        // Elimino i file precedenti per ridurre lo spazio
        $filePath = public_path().'/storage/groups/'.$query->logo;
        if(File::exists($filePath)){
          File::delete($filePath);
        }
        //
        $resize = '__160x160'.Str::random(64).'.jpg';
        //$normal_image = '__'.Str::random(64).'.jpg';
        $image = Image::make($a)->resize(160, 160)->encode('jpg');
        Storage::disk('groups')->put($resize, $image);
        //$image = Image::make($a)->encode('jpg');
        //Storage::disk('groups')->put($normal_image, $image);
        $query->logo = $resize;
      }
      if($request->_tp_sel == 1)
        $query->type = '0';
      elseif($request->_tp_sel == 2)
        $query->type = '1';
      $query->save();

      /*if($a){
        // Backup dell'immage non ridimensionata
        $backup = new BackupGroupsImages();
        $backup->img_title = $normal_image;
        $backup->group_id = $query->id;
        $backup->save();
      }*/
      return redirect()->back();
    }

    // Articoli
    public function ArticlePublish(Request $request)
    {
      $query = Articoli::find($request->_rq_token);
      if(\Auth::user()->id_gruppo > 0 && $query->id_gruppo == \Auth::user()->id_gruppo){
        $editor = Editori::find(\Auth::user()->id_gruppo);
        if($editor->direttore != \Auth::user()->id && $editor->type) // Revisione necessaria
          $query->status = '1'; // Rev
        else
          $query->status = '2'; // Non Rev

        $query->save();
      }
      return redirect('read/'.$query->slug);
    }

    public function ArticleSuspended(Request $request)
    {
      $query = Articoli::find($request->_rq_token);
      if((\Auth::user()->id_gruppo > 0 && $query->id_gruppo == \Auth::user()->id_gruppo) || ($query->autore == \Auth::user()->id)){

      }
      return redirect('read/'.$query->slug);
    }

    public function ArticleEdit(Request $request)
    {
      $query = Articoli::find($request->_rq_token);
      if((\Auth::user()->id_gruppo > 0 && $query->id_gruppo == \Auth::user()->id_gruppo) || (\Auth::user()->id == $query->autore)){
        $editore = Editori::find(\Auth::user()->id);
        return view('front.pages.edit_post',compact('query','editori'));
      }else{
        return redirect('read/'.$query->slug);
      }
    }

    public function ArticleDelete(Request $request)
    {
      $query = Articoli::find($request->_rq_token);
      if((\Auth::user()->id_gruppo > 0 && $query->id_gruppo == \Auth::user()->id_gruppo) || (\Auth::user()->id == $query->autore)){
        $query->delete();
      }
      return redirect('/');
    }
}
