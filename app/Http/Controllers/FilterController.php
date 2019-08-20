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

      $this->deleteFile( public_path('sf/aa/'.Auth::user()->copertina) );

      $fileName = rand().Str::random(14).'.jpg';

      $this->uploadFile($a, array(
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

      $query->copertina = asset('sf/aa/'. $fileName);
    }
    if($a = $request->avatar){
      $this->validate($request,[
        'avatar' => 'image|mimes:jpeg,jpg,png,gif',
      ],[
        'avatar.image' => 'Devi inserire un\'immagine',
        'avatar.mimes'  => 'Formato immagine non valido',
      ]);

      $this->deleteFile( public_path('sf/aa/'.Auth::user()->avatar) );

      if($a->getClientOriginalExtension() == 'gif'){
        $fileName = 'ac-'.rand().'.gif';
        // Insert gif animated
        File::copy($a->getRealPath(), public_path().'/sf/aa/'. $fileName);
      }else{

        $fileName = rand().Str::random(14).'.jpg';

        $this->uploadFile($a, array(
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
      $query->avatar = asset('sf/aa/'. $fileName);
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

      if(preg_match('/<img*/', $testo)) {
        $testo = $this->convertImages($testo, array('name' => Str::random(16).'.'.Str::random(32),'path' => public_path('sf/ct/')));
      }

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

        $query->testo = $testo;

      // Copertina
      if($a = $request->image) {
        $this->validate($request,[
          'image' => 'image|mimes:jpeg,jpg,png',
        ],[
          'image.image' => 'Devi inserire un\'immagine',
          'image.mimes'  => 'Formato immagine non valido',
        ]);

        $fileName = '__492x340'.Str::random(64).'.jpg';

        $this->uploadFile($a, array(
          'name' => $fileName,
          'path' => public_path('sf/ct/'),
          'width' => '492',
          'height' => '340',
          'mimetype' => 'jpg',
          'quality' => '100'
        ));

        /*$resize = '__492x340'.Str::random(64).'.jpg';
        $normal_image = '__'.Str::random(64).'.jpg';
        $image = Image::make($a)->resize(492, 340)->encode('jpg', 100);
        Storage::disk('articles')->put($resize, $image);*/
        $query->copertina = asset('sf/ct/'. $fileName);
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
      $testo = $request->document__text;
        $query = SavedArticles::find($request->id);
        if(!$query->suspended && (Auth::user()->id == $query->id_autore || Auth::user()->hasMemberOf($query->id_gruppo))) {
          if($query->testo) {
            if(preg_match('/;base64,/', $testo)) {
              $testo = $this->Base64ToUrl($testo, 'articles', Str::random(8).'.'.Str::random(16).'.jpg');
            }
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
      $testo = $request->document__text;

      if(preg_match('/<img*/', $testo)) {
        $testo = $this->convertImages($testo, array('name' => Str::random(16).'.'.Str::random(32),'path' => public_path('sf/ct/')));
      }

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

            $this->deleteFile( public_path('sf/ct/'. $query->copertina) );

            $fileName = '__492x340'.Str::random(64).'.jpg';

            $this->uploadFile($a, array(
              'name' => $fileName,
              'path' => public_path('sf/ct/'),
              'width' => '492',
              'height' => '340',
              'mimetype' => 'jpg',
              'quality' => '100'
            ));

            $query->copertina = asset('sf/ct/'. $fileName);
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

        /*if($request->_l_sel_ == '1') {
            $query->license = '1';
        } else {
            $query->license = '2';
        }*/

        if($a = $request->image){
          $this->validate($request,[
            'image' => 'image|mimes:jpeg,jpg,png',
          ],[
            'image.image' => 'Devi inserire un\'immagine',
            'image.mimes'  => 'Formato immagine non valido',
          ]);

          $this->deleteFile( public_path('sf/ct/'. $query->copertina) );

          $fileName = '__492x340'.Str::random(64).'.jpg';

          $this->uploadFile($a, array(
            'name' => $fileName,
            'path' => public_path('sf/ct/'),
            'width' => '492',
            'height' => '340',
            'mimetype' => 'jpg',
            'quality' => '100'
          ));

          $query->copertina = asset('sf/ct/'. $fileName);
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

    /*
      @return: image url
    */
    public function uploadFile($img, $details)
    {
      if(File::isDirectory($details['path'])) {
        $image = Image::make($img)->fit($details['width'], $details['height'])->save( $details['path'].$details['name'] );
      } else {
        File::makeDirectory($details['path'], 0777, true);
        return $this->uploadFile($img, $details);
      }
      return $details['name'];
    }

    /*
      @description: delete file if exist
    */
    public function deleteFile($filePath)
    {
        if(File::exists($filePath)){
          File::delete($filePath);
        }
    }

    public function convertImages($source, $file)
    {
        $img = preg_match_all('/[<]img src=[^>]+/', $source, $output);
        foreach($output[0] as $value) {
          $src = preg_split('/src="*|"/', $value);
          if( preg_match('/data:[a-z]+\/[a-zA-Z]+;base64,(.*)/', $src[1]) ) {
            $info = explode('/', preg_split('/data:|;base64,(.*)/', $src[1])[1]);
            $type = $info[0];
            $mimetype = $info[1];
            $base64[0][] = $src[1];
            if($type == "image" && in_array($mimetype, ['png','jpeg','jpg'])) {
              /***************************************************************/
              $img = file_get_contents($src[1]);
              $name = $file['name'].'.'.explode('/', getimagesizefromstring($img)['mime'])[1];
              $this->uploadFile($img, array(
                'name' => $name,
                'path' => $file['path'],
                'width' => getimagesizefromstring($img)[0],
                'height' => getimagesizefromstring($img)[1]
              ));
              /***************************************************************/
              $base64[1][] = asset('sf/ct/'. $name);
            } else {
              $base64[1][] = '';
            }
          } elseif( !$this->equals('/[a-z:]*\/\/[ww*.*]*|\/(.*)/', $src[1], \URL::to('/')) ) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $src[1]);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $img = curl_exec($ch);
            $encode = base64_encode($img);
            $base64[0][] = $encode;
            $mimetype = explode('/', curl_getinfo($ch, CURLINFO_CONTENT_TYPE))[1];
            if(in_array($mimetype, ['png','jpeg','jpg'])) {
              $name = $file['name'].'.'.$mimetype;
              $this->uploadFile($img, array(
                'name' => $name,
                'path' => $file['path'],
                'width' => getimagesizefromstring($img)[0],
                'height' => getimagesizefromstring($img)[1]
              ));
              curl_close($ch);
              $base64[1][] = asset('sf/ct/'. $name);
            } else {
              // formato non accettabile
              // ignora l'immagine
              $source = str_replace($value.'>', '', $source);
            }
          }
        }
        if(isset($base64)) {
          return str_replace($base64[0], $base64[1], $source);
        } else {
          return $source;
        }
    }

    public function equals($pattern, $first, $second)
    {
        $first = preg_split($pattern, $first)[1];
        $second = preg_split($pattern, $second)[1];
        if ($first === $second) {
          return true;
        } else {
          return false;
        }
    }
}
