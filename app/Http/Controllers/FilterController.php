<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Requests\UpdateUsername;
use App\Http\Requests\UpdatePassword;

use App\Models\User;
use App\Models\SavedArticles;
use App\Models\Articoli;
use App\Models\Editori;
use App\Models\BackupAccountsImages;
use App\Models\BackupGroupsImages;
use App\Models\Notifications;
use App\Models\PublisherRequest;

use App\Models\ArticleComments;
use App\Models\AnswerComments;

use App\Models\ReportedArticles;
use App\Models\ReportedUsers;
use App\Models\ReportedComments;
use App\Models\ReportedAComments;
use App\Models\ArticleScore;

use Validator;
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
    $query->name = $request->name;
    $query->surname = $request->surname;
    if($a = $request->cover){
      $this->validate($request,[
        'cover' => 'image|mimes:jpeg,jpg,png,gif',
      ],[
        'cover.image' => 'Devi inserire un\'immagine',
        'cover.mimes'  => 'Formato immagine non valido',
      ]);
      $filePath = public_path().'/storage/accounts/'.Auth::user()->copertina;
      if(File::exists($filePath)){
        File::delete($filePath);
      }
      $fileName = rand().'.jpg';
      $image = Image::make($a)->resize(1110, 350)->encode('jpg', 100);
      Storage::disk('accounts')->put($fileName, $image);
      $query->copertina = Storage::disk('accounts')->url('accounts/'.$fileName);
    }
    if($a = $request->avatar){
      $this->validate($request,[
        'avatar' => 'image|mimes:jpeg,jpg,png,gif',
      ],[
        'avatar.image' => 'Devi inserire un\'immagine',
        'avatar.mimes'  => 'Formato immagine non valido',
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
        //crop($request->width[0],$request->height[0],$request->x[0],$request->y[0])
        $image = Image::make($a)->resize(160, 160)->encode('jpg', 100);
        Storage::disk('accounts')->put($fileName, $image);
      }
      $query->avatar = Storage::disk('accounts')->url('accounts/'.$fileName);
    }
    // Socials
      $query->biography = $request->bio;
      $query->facebook = $request->facebook;
      $query->instagram = $request->instagram;
      $query->linkedin = $request->linkedin;

    $query->save();
    return redirect('settings');
  }

  public function postChangeUsername(UpdateUsername $request)
  {
    $query = User::find(Auth::user()->id);
    $query->slug = Str::slug($request->slug,'-');
    $query->save();
    return redirect('settings');
  }

  public function postChangePassword(Request $request)
  {
    $query = User::find(Auth::user()->id);

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
    return redirect('settings');
  }

  public function postAccountDelete(Request $request)
  {
    $query = User::find(Auth::user()->id);

    if(!$query->suspended){
      $query->cron = '1';
      $query->save();
      // Avvio il processo di eliminazione dell'account
      $task = new \App\Models\AccountDeletionRequest();
      $task->user_id = $query->id;
      $task->expired_at = Carbon::now()->addDays(30);
      $task->save();
      Auth::logout();
    }
    return redirect('/');
  }

  // GROUP

  public function deleteGroup($id, Request $request)
  {
    $query = Editori::find($id);
    if($query->direttore == Auth::user()->id){
      if(!$query->suspended){
        // elimino tutti gli articoli scritti dalla redazione
        $articoli = Articoli::where('id_gruppo', $query->id);
        foreach($articoli->get() as $value) {

        }
        $articoli->delete();

        $pub_request = PublisherRequest::where('publisher_id', $query->id);
        foreach($pub_request->get() as $value) {
          Notifications::where('type', '1')->where('content_id', $value->id)->delete();
        }
        $pub_request->delete();

        $components = collect(explode(',', $query->componenti));
        // elimino tutti i membri dal gruppo
        foreach($components as $value){
          $query2 = User::find($value);
          $collection = collect(explode(',', $query2->id_gruppo));
          $query2->id_gruppo = $collection->reject(function($val) use ($query) {
            return $val == $query->id;
          })->implode(',');
          $query2->save();
        }
        $query->delete();
      }
      return redirect('/');
    }
    return redirect($query->slug);
  }

    public function ArticleReport(Request $request)
    {
      $query = Articoli::find($request->id);
      if($request->ajax()){
        if($query->id_autore != Auth::user()->id){
          $query2 = new ReportedArticles();
          $query2->user_id = Auth::user()->id;
          $query2->article_id = $query->id;
          $query2->report = $request->selector;
          $query2->report_text = $request->text;
          $query2->report_token = Str::random(32);
          $query2->save();
        }
      }
    }

    public function UserReport(Request $request)
    {
      $query = User::find($request->id);
      if($request->ajax()){
        if($query->id_autore != Auth::user()->id){
          $query2 = new ReportedUsers();
          $query2->user_id = Auth::user()->id;
          $query2->reported_id = $query->id;
          $query2->report = $request->selector;
          $query2->report_text = $request->text;
          $query2->report_token = Str::random(32);
          $query2->save();
        }
      }
    }

    public function CommentReport(Request $request)
    {
      $query = ArticleComments::find($request->id);
      if($request->ajax()){
        if($query->user_id != Auth::user()->id){
          $query2 = new ReportedComments();
          $query2->user_id = Auth::user()->id;
          $query2->comment_id = $query->id;
          $query2->report = $request->selector;
          $query2->report_text = $request->text;
          $query2->report_token = Str::random(32);
          $query2->save();
        }
      }
    }

    public function ACommentReport(Request $request)
    {
      $query = AnswerComments::find($request->id);
      if($request->ajax()){
        if($query->user_id != Auth::user()->id){
          $query2 = new ReportedAComments();
          $query2->user_id = Auth::user()->id;
          $query2->answer_id = $query->id;
          $query2->report = $request->selector;
          $query2->report_text = $request->text;
          $query2->report_token = Str::random(32);
          $query2->save();
        }
      }
    }

    // Publishers

    public function postNewPublisher(Request $request)
    {
      $this->validate($request, [
        'name' => 'required|min:3|max:30|unique:editori',
        'description' => 'max:160',
      ], [
        'name.required' => 'Il nome della redazione è obbligatorio',
        'name.min' => 'Il nome è troppo corto',
        'name.max' => 'Il nome è troppo lungo',
        'name.unique' => 'Questo nome è stato già preso',
        'description.max' => 'La descrizione è troppo lunga',
      ]);
      $query = new Editori();
      $query->name = $request->name;
      $query->componenti = Auth::user()->id;
      if($a = $request->cover){
        $this->validate($request,[
          'cover' => 'image|mimes:jpeg,jpg,png',
        ],[
          'cover.image' => 'Devi inserire un\'immagine',
          'cover.image'  => 'Formato immagine non valido',
        ]);
        if(Storage::disk('groups')->exists($query->cover)){
          Storage::disk('groups')->delete($query->cover);
        }
        $resize = '__160x160'.Str::random(64).'.jpg';
        $image = Image::make($a)->resize(1110, 350)->encode('jpg', 100);
        Storage::disk('groups')->put($resize, $image);
        $query->cover = Storage::disk('groups')->url('groups/'.$resize);
      }
      if($a = $request->avatar){
        $this->validate($request,[
          'avatar' => 'image|mimes:jpeg,jpg,png',
        ],[
          'avatar.image' => 'Devi inserire un\'immagine',
          'avatar.image'  => 'Formato immagine non valido',
        ]);
        if(Storage::disk('groups')->exists($query->avatar)){
          Storage::disk('groups')->delete($query->avatar);
        }
        $fileName = rand().'.jpg';
        $image = Image::make($a)->encode('jpg', 100);
        Storage::disk('groups')->put($fileName, $image);
        $query->avatar = Storage::disk('groups')->url('groups/'.$fileName);
      }
      $query->direttore = Auth::user()->id;
      $query->followers_count = '0';
      $query->biography = $request->description;
      $query->save();
      $query->slug = Str::slug($query->name,'-');
      $query->save();
      $user = User::find(Auth::user()->id);
      if(!empty($user->id_gruppo)) {
        $user->id_gruppo = $user->id_gruppo.','.$query->id;
      } else {
        $user->id_gruppo = $query->id;
      }
      $user->save();
      return redirect($query->slug);
    }

    public function postPublisherSettings($slug,Request $request)
    {
      $query = Editori::where('slug',$slug)->first();
      if(!$query->suspended){
        $query->name = $request->name;
        if($a = $request->description){
          $this->validate($request,[
            'description' => 'max:160',
          ],[
            'description.max' => 'La descrizione è troppo lunga',
          ]);
          $query->biography = $request->description;
        }
        if($a = $request->cover){
          $this->validate($request,[
            'cover' => 'image|mimes:jpeg,jpg,png',
          ],[
            'cover.image' => 'Devi inserire un\'immagine',
            'cover.mimes'  => 'Formato immagine non valido',
          ]);
          if(Storage::disk('groups')->exists($query->cover)){
            Storage::disk('groups')->delete($query->cover);
          }
          $resize = '__160x160'.Str::random(64).'.jpg';
          $image = Image::make($a)->resize(1110, 350)->encode('jpg', 100);
          Storage::disk('groups')->put($resize, $image);
          $query->cover = Storage::disk('groups')->url('groups/'.$resize);
        }
        if($a = $request->avatar){
          $this->validate($request,[
            'avatar' => 'image|mimes:jpeg,jpg,png',
          ],[
            'cover.image' => 'Devi inserire un\'immagine',
            'avatar.mimes'  => 'Formato immagine non valido',
          ]);
          if(Storage::disk('groups')->exists($query->avatar)){
            Storage::disk('groups')->delete($query->avatar);
          }
          $fileName = rand().'.jpg';
          $image = Image::make($a)->resize(160, 160)->encode('jpg', 100);
          Storage::disk('groups')->put($fileName, $image);
          $query->avatar = Storage::disk('groups')->url('groups/'.$fileName);
        }
        $query->save();
      }
      return redirect()->back();
    }

    public function promoteUser(Request $request)
    {
      if($request->ajax()) {
        $query = Editori::find($request->publisher_id);
        if(!$query->suspended && $query->direttore == Auth::user()->id && $request->id != Auth::user()->id) {
          $user = User::find($request->id);

          if($user->hasMemberOf($query->id)) {
            $query->direttore = $user->id;
            $query->save();
          }
        }
      }
    }

    public function firedUser(Request $request)
    {
      if($request->ajax()) {
        $query = Editori::find($request->publisher_id);
        if(!$query->suspended && $query->direttore == Auth::user()->id && $request->id != Auth::user()->id) {
          $user = User::find($request->id);

          if($user->hasMemberOf($request->publisher_id)) {
            $collection = collect(explode(',', $user->id_gruppo))->filter(function($value, $key) use ($request) {
              return $value != "" && $value != $request->publisher_id;
            });

            $query->componenti = collect(explode(',', $query->componenti))->filter(function($value, $key) use ($user) {
              return $value != "" && $value != $user->id;
            })->implode(',');
            $query->save();

            $user->id_gruppo = $collection->implode(',');
            $user->save();
          }
        }
      }
    }

    // Articoli
    public function postWrite(Request $request)
    {
      $input = $request->all();
      $testo = $request->document__text;
      $input['document__text'] = strip_tags(str_replace('&nbsp;','',$request->document__text));
      $request->replace($input);

      if($request->save) {
        $this->validate($request,[
          'document__title' => 'required|max:191',
        ],[
          'document__text.max' => 'Titolo troppo lungo',
        ]);

        $query = new SavedArticles();
      } else {
        $this->validate($request,[
          'document__title' => 'required|max:191',
          'document__text' => 'required'
        ],[
          'document__title.required' => 'Il titolo dell\'articolo è obbligatorio',
          'document__text.required' => 'Non è consentito pubblicare un articolo senza contenuto',
          'document__text.max' => 'Titolo troppo lungo',
        ]);

        $query = new Articoli();
      }

      $query->titolo = $request->document__title;
      $query->tags = str_slug($request->tags, ',');

      if($request->_ct_sel_ > 0) {
          $query->topic_id = $request->_ct_sel_;
      }

      if($testo) {
        $dom = new \DomDocument('1.0', 'UTF-8');
        $dom->loadHtml(mb_convert_encoding($testo, 'HTML-ENTITIES', 'UTF-8'));
        $images = $dom->getElementsByTagName('img');

        foreach($images as $img){
          $data = $img->getAttribute('src');
          $dim = $img->getAttribute('style');

          $data = explode(';base64,', $data);
          $data = base64_decode($data[1]);

          $dim = preg_split('/;\s/', $dim);

          $image_name = Str::random(64).'.jpg';

          $image = Image::make($data);

          if(count($dim) > 0) {
              $image->resize($dim[0], null);
          } elseif(count($dim) > 1) {
              $image->resize($dim[0], $dim[1]);
          }
          $image->encode('jpg', 100);

          Storage::disk('articles')->put($image_name, $image);

          $img->removeAttribute('src');
          $img->removeAttribute('style');
          $img->removeAttribute('data-filename');
          $img->setAttribute('src', Storage::disk('articles')->url('articles/'.$image_name));
        }
        $testo = $dom->saveHTML();

        $query->testo = $testo;
      }

      // Copertina
      if($a = $request->image) {
        $this->validate($request,[
          'image' => 'image|mimes:jpeg,jpg,png',
        ],[
          'image.image' => 'Devi inserire un\'immagine',
          'image.mimes'  => 'Formato immagine non valido',
        ]);
        $resize = '__492x340'.Str::random(64).'.jpg';
        $normal_image = '__'.Str::random(64).'.jpg';
        $image = Image::make($a)->resize(492, 340)->encode('jpg', 100);
        Storage::disk('articles')->put($resize, $image);
        $query->copertina = Storage::disk('articles')->url('articles/'.$resize);
      }

      if($request->_au > 0) {
        $publisher = Editori::find($request->_au);
        if(Auth::user()->hasMemberOf($publisher->id) && !$publisher->suspended) {
          $query->id_gruppo = $request->_au;
        }
      }

      if($request->_l_sel_ == '1') {
          $query->license = '1';
      } else {
          $query->license = '2';
      }

      $query->id_autore = \Auth::user()->id;
      $query->save();

      // Slug
      if($request->save) {
        $query->slug = uniqid();
        $query->save();
        return redirect('read/archive/'.$query->slug);
      } else {
        $query->slug = str_slug($query->id.'-'.$query->titolo,'-');
        $query->save();

        return redirect('read/'.$query->slug);
      }

    }

    public function ArticlePublish(Request $request)
    {
        $query = SavedArticles::find($request->id);
        if(!$query->suspended && (Auth::user()->id == $query->id_autore || Auth::user()->hasMemberOf($query->id_gruppo))) {
          if($query->testo) {
            $query2 = new Articoli();
            $query2->topic_id = $query->topic_id;
            $query2->titolo = $query->titolo;
            $query2->tags = $query->tags;
            $query2->testo = $query->testo;
            $query2->copertina = $query->copertina;
            $query2->id_gruppo = $query->id_gruppo;
            $query2->id_autore = $query->id_autore;
            $query2->license = $query->license;
            $query2->bot_message = '0';
            $query2->save();
            $query2->slug = $query2->id.'-'.str_slug($query2->titolo, '-');
            $query2->save();
            $query->delete();

          } else {
            return redirect('');
          }
        }
        return redirect('read/'.$query2->slug);
    }

    public function postArticleEdit($id, Request $request)
    {
      $input = $request->all();
      $testo = $request->document__text;
      $input['document__text'] = strip_tags(str_replace('&nbsp;','',$request->document__text));
      $request->replace($input);


      $query = Articoli::where('slug', $id)->first();

      if(empty($query)) {
        $this->validate($request, [
          'document__title' => 'required|max:191',
        ],[
          'document__title.required' => 'Il titolo dell\'articolo è obbligatorio',
        ]);

        $query = SavedArticles::where('slug', $id)->first();
        $query->titolo = $request->document__title;

        if(!Auth::user()->suspended && ($query->id_gruppo > 0 && Auth::user()->hasMemberOf($query->id_gruppo) || Auth::user()->id == $query->id_autore)) {

          $query->tags = str_slug($request->tags, ',');
          $query->testo = $testo;

          if($request->_ct_sel_ > 0) {
              $query->topic_id = $request->_ct_sel_;
          }

          if($request->_l_sel_ == '1') {
              $query->license = '1';
          } else {
              $query->license = '2';
          }

          if($a = $request->image){
            $this->validate($request,[
              'image' => 'image|mimes:jpeg,jpg,png',
            ],[
              'image.image' => 'Devi inserire un\'immagine',
              'image.mimes'  => 'Formato immagine non valido',
            ]);
            $filePath = public_path().'/storage/articles/'.$query->copertina;
            if(File::exists($filePath)){
              File::delete($filePath);
            }
            $resize = '__492x340'.Str::random(64).'.jpg';
            $image = Image::make($a)->resize(492, 340)->encode('jpg');
            Storage::disk('articles')->put($resize, $image);
            $query->copertina = Storage::disk('articles')->url('articles/'.$resize);
          }
          $query->save();
        }
        return redirect('read/archive/'.$query->slug);
      }

      $this->validate($request, [
        'document__text' => 'required',
      ],[
        'document__text.required' => 'Non è consentito pubblicare un articolo senza contenuto'
      ]);

      if(!Auth::user()->suspended && ($query->id_gruppo > 0 && Auth::user()->hasMemberOf($query->id_gruppo) || Auth::user()->id == $query->id_autore)) {

        $query->tags = str_slug($request->tags, ',');
        $query->testo = $testo;

        if($request->_ct_sel_ > 0) {
            $query->topic_id = $request->_ct_sel_;
        }

        if($request->_l_sel_ == '1') {
            $query->license = '1';
        } else {
            $query->license = '2';
        }

        if($a = $request->image){
          $this->validate($request,[
            'image' => 'image|mimes:jpeg,jpg,png',
          ],[
            'image.image' => 'Devi inserire un\'immagine',
            'image.mimes'  => 'Formato immagine non valido',
          ]);
          $filePath = public_path().'/storage/articles/'.$query->copertina;
          if(File::exists($filePath)){
            File::delete($filePath);
          }
          $resize = '__492x340'.Str::random(64).'.jpg';
          $image = Image::make($a)->resize(492, 340)->encode('jpg');
          Storage::disk('articles')->put($resize, $image);
          $query->copertina = Storage::disk('articles')->url('articles/'.$resize);
        }
        $query->save();
      }
      return redirect('read/'.$query->slug);
    }

    public function ArticleDelete(Request $request)
    {
        $query = Articoli::find($request->id);
        if(empty($query)) {
          $query = SavedArticles::find($request->id);
        }
        if(!$query->suspended && (Auth::user()->hasMemberOf($query->id_gruppo) || Auth::user()->id == $query->id_autore)) {
          // elimino le notifiche relative all'articolo
          ArticleScore::where('article_id', $query->id)->delete();
          Notifications::where('type', '3')->where('content_id', $query->id)->delete();
          $query->delete();
        }
        return redirect('/');
    }
}
