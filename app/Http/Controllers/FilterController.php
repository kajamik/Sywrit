<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Requests\UpdateUsername;
use App\Http\Requests\UpdatePassword;

use App\Models\User;
use App\Models\SavedArticles;
use App\Models\Articoli;
use App\Models\Notifications;

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

        uploadFile($a, array(
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
            if(preg_match('/<img*/', $testo)) {
              $testo = $this->convertImages($testo, array('name' => Str::random(16).'.'.Str::random(32),'path' => public_path('sf/ct/')));
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

            deleteFile( public_path('sf/ct/'. $query->copertina) );

            $fileName = '__492x340'.Str::random(64).'.jpg';

            uploadFile($a, array(
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

          deleteFile( public_path('sf/ct/'. $query->copertina) );

          $fileName = '__492x340'.Str::random(64).'.jpg';

          uploadFile($a, array(
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
}
