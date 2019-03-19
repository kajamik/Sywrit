<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Articoli;
use App\Models\Editori;
use App\Models\BackupAccountsImages;
use App\Models\BackupGroupsImages;
use App\Models\Notifications;

use Carbon\Carbon;
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
      'surname' => 'required|string|min:3|max:12'
    ]);
    $query = User::find(\Auth::user()->id);
    $query->nome = $request->name;
    $query->cognome = $request->surname;
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
    // Socials
      //$query->biography = $bio;
      $query->facebook = $request->facebook;
      $query->instagram = $request->instagram;
      $query->linkedin = $request->linkedin;

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
      'slug.unique' => ':username già esistente',
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
    $query = User::find($request->id);
    if(Auth::user()->isDirector() && !$query->haveGroup()){
      $message = new Notifications();
      $message->sender_id = Auth::user()->id;
      $message->target_id = $query->id;
      $message->text = $request->text;
      $message->type = '1'; // group request
      $message->marked = '0';
      $message->save();
      $query->notifications_count++;
      $query->save();
    }
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

    public function deleteGroup(Request $request)
    {
      $query = Editori::find(Auth::user()->id_gruppo);
      if($query->direttore == Auth::user()->id){
          if(!$query->accesso){
            if(!$request->button){
              $query->delete();
            }else{
              $query->accesso = '1';
            }
          }else{
            $query->accesso = '0';
          }
          $query->save();
      }
      return redirect($query->slug.'/settings/edit');
    }

    public function ArticleReport(Request $request)
    {
      $query = new \App\Models\ActivitiesReports();
      $query->user_id = Auth::user()->id;
      $query->article_id = $request->id;
      $query->report = $request->selector;
      $query->report_text = $request->text;
      $query->report_token = Str::random(64);
      $query->save();
    }

    /** Publishers **/

    public function postNewPublisher(Request $request)
    {
      if(Auth::user()->haveGroup())
        abort(404);

      $this->validate($request, [
        'publisher_name' => 'required|string|min:4|max:16',
      ], [
        'publisher_name.required' => 'Nome editoria richiesto',
        'publisher_name.string' => 'Il nome editoria dev\'essere una stringa'
      ]);
      $query = new Editori();
      $query->nome = $request->publisher_name;
      $query->componenti = Auth::user()->id;
      $query->direttore = Auth::user()->id;
      $query->followers_count = '0';
      //$query->biography = $request->publisher_bio;
      $query->avvisi = '0';
      $query->accesso = '1';
      $query->save();
      $query->slug = Str::slug($query->nome,'-');
      $query->save();
      $user = User::find(Auth::user()->id);
      $user->id_gruppo = $query->id;
      $user->save();
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

    public function promoteUser(Request $request)
    {
      $user = User::where('id',$request->id)->first();
      $query = Editori::where('id',Auth::user()->id)->first();

      $query->direttore = $user->id;
      $query->save();
    }

    public function firedUser(Request $request)
    {
      $user = User::where('id',$request->id)->first();
      $query = Editori::where('id',Auth::user()->id_gruppo)->first();

      $collection = collect(explode(',',$query->componenti));
      $collection->splice($collection->search($user->id),1);
      $query->componenti = $collection->implode(',');
      $query->save();
      $user->id_gruppo = null;
      $user->save();
    }

    /**************************************/

    // Articoli
    public function postWrite(Request $request)
    {
      $this->validate($request,[
        'document__title' => 'required|min:5|max:30',
        'document__text' => 'required'
      ],[
        'document__title.required' => 'Il titolo dell\'articolo è obbligatorio',
        'document__text.required' => 'Non è consentito pubblicare un articolo senza contenuto',
        'document__text.min' => 'Contenuto troppo breve'
      ]);
      $query = new Articoli();
      $query->titolo = $request->document__title;
      $query->tags = str_slug($request->tags,',');
      $query->testo = $request->document__text;
      if($a = $request->image){
        $resize = '__492x340'.Str::random(64).'.jpg';
        $normal_image = '__'.Str::random(64).'.jpg';
        $image = Image::make($a)->crop($request->width[0],$request->height[0],$request->x[0],$request->y[0])->resize(492, 340)->encode('jpg');
        Storage::disk('articles')->put($resize, $image);
        $image = Image::make($a)->encode('jpg');
        Storage::disk('articles')->put($normal_image, $image);
        $query->copertina = $resize;
      }
      if(\Auth::user()->id_gruppo > 0 && $request->_au == 2){
        $query->id_gruppo = Auth::user()->id_gruppo;
      }
      $query->autore = \Auth::user()->id;
      if($request->save){
        $query->status = '0';
      }else{
        $query->status = '1'; // pubblicato
        // Sistema punti
        $query->score = 0;
        $points = 0;
        foreach(explode('<br>', $request->document__text) as $value) {
          $pattern = '/<h2[^>]*>(.*?)<\/h2><p[^>]*>(.*?)<\/p>/is';
          $headers = preg_match( $pattern, $request->document__text );
          if($headers) {
            if($points < 2) {

            } else {
              if($points > 0) {
                $points -= 0.15;
              }
            }
          }
          // END
        }
      /*  $user = User::find(Auth::user()->id);
        if($words == (1000 * $user->rank)+1) {
          $user->rank += 1;
          $user->points = $points;
        } else {
          $user->points += $words;
        }
        $user->save();*/
    }
      $query->count_view = '0';
      $query->likes_count = '0';
      $query->save();

      // Slug
        $query->slug = str_slug($query->id.'-'.$query->titolo,'-');
        $query->save();
      //
      // Notifications
      if(!empty(Auth::user()->getPublisherInfo()->followers)) {
        foreach(explode(',',Auth::user()->getPublisherInfo()->followers) as $value) {
          if($value != Auth::user()->id) {
            $notifiche = new \App\Models\Notifications();
            if($query->id_gruppo != null){
              $notifiche->sender_id = Auth::user()->getPublisherInfo()->id;
              $notifiche->type = '3';
            }else{
              $notifiche->sender_id = Auth::user()->id;
              $notifiche->type = '2';
            }
            $notifiche->target_id = $value;
            $notifiche->content_id = $query->id;
            $notifiche->marked = '0';
            $notifiche->save();
            $user = User::find($value);
            $user->notifications_count++;
            $user->save();
          }
        }
      }

      return redirect('read/'.$query->slug);
    }
    
    public function ArticlePublish(Request $request)
    {
      $query = Articoli::find($request->_rq_token);
      if(Auth::user()->id_gruppo > 0 && $query->id_gruppo == Auth::user()->id_gruppo){
        $query->status = '1';
        $query->published_at = Carbon::now();
        $query->save();
      }
      return redirect('read/'.$query->slug);
    }

    public function postArticleEdit($id, Request $request)
    {
      $this->validate($request, [
        'document__text' => 'required',
      ],[
        'document__text.required' => 'Non è consentito pubblicare un articolo senza contenuto'
      ]);
      $query = Articoli::find($id);
      if((\Auth::user()->id_gruppo > 0 && $query->id_gruppo == \Auth::user()->id_gruppo) || (\Auth::user()->id == $query->autore)){
        $query->testo = $request->document__text;
        if($a = $request->image){
          $resize = '__492x340'.Str::random(64).'.jpg';
          $normal_image = '__'.Str::random(64).'.jpg';
          $image = Image::make($a)->crop($request->width[0],$request->height[0],$request->x[0],$request->y[0])->resize(492, 340)->encode('jpg');
          Storage::disk('articles')->put($resize, $image);
          $image = Image::make($a)->encode('jpg');
          Storage::disk('articles')->put($normal_image, $image);
          $query->copertina = $resize;
        }
        $query->save();
      }
      return redirect('read/'.$query->slug);
    }

    public function ArticleDelete(Request $request)
    {
      $query = Articoli::find($request->_rq_token);
      if((Auth::user()->id_gruppo > 0 && $query->id_gruppo == \Auth::user()->id_gruppo) || (\Auth::user()->id == $query->autore)){
        $query->delete();
      }
      return redirect('/');
    }
}
