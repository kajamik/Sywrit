<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use App\Mail\SupportEmail;

use Response;
use Auth;

// Models
use App\Models\Editori;
use App\Models\Articoli;
use App\Models\User;
use App\Models\Notifications;
use App\Models\PublisherRequest;
use App\Models\ArticleComments;
use App\Models\AnswerComments;
use App\Models\ArticleLikes;

// Achievements
use App\Achievements\FirstComment;

// Notifications
use App\Notifications\NotifyArticleOwner;

class AjaxController extends Controller
{

  public function __construct(Request $request)
  {
    if(!$request->ajax()){
      abort(404);
    }
  }

  public function rate(Request $request)
  {
    /*if(!empty($article->id_gruppo)) {

      $editore = \DB::table('editori')->where('id', $article->id_gruppo)->first();
      $components = collect(explode(',',$editore->componenti))->filter(function ($value, $key) {
        return ($value != "" && $value != Auth::user()->id);
      });

      foreach($components as $value) {
        $noty = new Notifications();
        $noty->sender_id = Auth::user()->id;
        $noty->target_id = $value;
        $noty->content_id = $article->id;
        $noty->type = '2';
        $noty->read = '0';
        $noty->save();
      }
    } else {
        $noty = new Notifications();
        $noty->sender_id = Auth::user()->id;
        $noty->target_id = $article->id_autore;
        $noty->content_id = $article->id;
        $noty->type = '2';
        $noty->read = '0';
        $noty->save();
    }*/

      $article_id = $request->id;

      $liked = ArticleLikes::where('user_id', Auth::user()->id)->where('article_id', $article_id);

      $article = Articoli::where('id', $article_id)->first();

      if(!empty($article)){

        if(!$liked->count()) {
          $query = new ArticleLikes();
          $query->user_id = Auth::user()->id;
          $query->article_id = $article->id;
          $query->save();
        } else {
          $liked->delete();
        }

        return view('front.components.article.rate')->with(['query' => $article]);
      }
  }

  public function SearchLiveData(Request $request)
  {
    $search = $request->q;

    if(!is_null($search)){
      $query = User::where(\DB::raw("concat(name, ' ', surname)"), 'like', '%'.$search.'%')
            ->limit(5)
            ->get();

      $query2 = Articoli::where('titolo', 'like', '%'. $search .'%')
              ->limit(5)
              ->get();

      $query3 = Articoli::where('tags', 'like', '%'. $search. '%')
                ->limit(5)
                ->get();

      /*$query4 = Editori::where('name', 'like', '%'. $search. '%')
              ->limit(5)
              ->get();*/

      $query2 = $query2->merge($query3);

      return view('front.pages.livesearch')->with(['query' => $query, 'query2' => $query2, 'key' => $search]);
    }
  }

  public function getStateNotifications(Request $request)
  {
    $count = $request->msg_count;
    $noty = \DB::table('notifications')->where('target_id', Auth::user()->id)->where('marked','0');

    if($noty->count() > $count){
      $queries = array();
      foreach($noty->get() as $value){
        array_push($queries, \DB::table('articoli')->join('utenti', 'articoli.id_autore', '=', 'utenti.id')->addSelect('articoli.titolo as titolo','articoli.slug as article_slug','utenti.name as user_name','utenti.surname as user_surname')->where('articoli.id', $value->content_id)->first());
      }
      return Response::json(['count' => $noty->count(), 'query' => $queries]);
    }
  }

  public function getNotifications(Request $request)
  {
    $LIMIT = 3;
    $query = Notifications::where('target_id',Auth::user()->id)->orderBy('created_at','desc')->get();
    if(Auth::user()->notifications_count > 0){
      $user = User::find(Auth::user()->id);
      $user->notifications_count = '0';
      $user->save();
    }
    return view('front.pages.livenotifications')->with(['query' => $query]);
  }

  public function deleteAllNotifications()
  {
    PublisherRequest::where('target_id', Auth::user()->id)->delete();
    Notifications::where('target_id', Auth::user()->id)->delete();
  }

  public function deleteNotification(Request $request)
  {
    $query = Notifications::where('id', $request->id)->first();
    if($query->type == '1') {
      PublisherRequest::where('id', $query->content_id)->delete();
    }
    $query->delete();
  }

  /*** Commenti ***/

  public function loadComments(Request $request)
  {
    $current_page = ($request->q) ? $request->q : 1;
    $LIMIT = 6;

    $comments = ArticleComments::where('article_id', $request->id)->orderBy('created_at','desc')->skip($LIMIT * ($current_page-1))->take($LIMIT)->get();

    return view('front.components.ajax.loadComments')->with(['query' => $comments]);
  }

  public function postComments(Request $request)
  {
    $post = $request->post;
    $query = Articoli::where('id', $request->id)->first();

    if(!empty($post)){

      $query2 = ArticleComments::create([
        'user_id' => Auth::user()->id,
        'text' => $post,
        'article_id' => $query->id
      ]);

      $article = Articoli::find($query2->article_id);
      User::find($article->getAutore->id)->notify(new NotifyArticleOwner());

      return view('front.components.ajax.uploadComment')->with(['post' => $query2]);
    }
  }

  /*** Risposte ***/

  public function loadAnswers(Request $request)
  {
      /*$current_page = ($request->q) ? $request->q : 1;
      $LIMIT = 6;*/

    $answers = AnswerComments::where('comment_id', $request->id)->orderBy('created_at','asc')->get();//->skip($LIMIT * ($current_page-1))->take($LIMIT)->get();

    return view('front.components.ajax.loadCommentAnswers')->with(['query' => $answers]);
  }

  public function postAnswers(Request $request)
  {
    $post = $request->post;
    if(!empty($post)){
      $query = new AnswerComments();
      $query->user_id = Auth::user()->id;
      $query->text = $post;
      $query->comment_id = $request->id;
      $query->save();
      return view('front.components.ajax.uploadAnswers')->with(['post' => $query]);
    }
  }

  /*** Publishers ***/


    public function inviteGroup(Request $request)
    {
      if($request->ajax()) {
        $publisher_id = $request->selector;
        $collection = collect(explode(',', Auth::user()->id_gruppo));

        $query = User::find($request->user_id);
        if($query->suspended) {
          return Response::json(['message' => 'Questo account è stato sospeso da un operatore']);
        }
        if(Auth::user()->hasFoundedGroup()) {
          $editore = \DB::table('editori')->where('id', $publisher_id)->first();
          if(!collect(explode(',', $editore->componenti))->some($query->id)) {
            $message = new PublisherRequest();
            $message->user_id = Auth::user()->id;
            $message->target_id = $query->id;
            $message->publisher_id = $publisher_id;
            $message->token = Str::random(32);
            $message->expired_at = null;
            $message->save();
            $noty = new Notifications();
            $noty->sender_id = Auth::user()->id;
            $noty->target_id = $query->id;
            $noty->content_id = $message->id;
            $noty->type = '1'; // group request
            $noty->marked = '0';
            $noty->save();
            $query->notifications_count++;
            $query->save();
            return Response::json(['message' => 'Richiesta di collaborazione inviata']);
          } else {
            return Response::json(['message' => 'Questo utente fa già parte di questa redazione']);
          }
        }
      }
    }

  public function acceptGroupRequest(Request $request)
  {
      $query = Notifications::where('id', $request->id)->first();
      $pub_request = PublisherRequest::where('id', $query->content_id)->first();

      if($query->target_id == Auth::user()->id) {
        $publisher = Editori::where('id', $pub_request->publisher_id)->first();
        $collection = collect(explode(',', $publisher->componenti));
        if(!$collection->some(Auth::user()->id)) {
          $collection->push(Auth::user()->id);
          $publisher->componenti = $collection->implode(',');
          $publisher->save();
          /***/
          $user = User::find(Auth::user()->id);
          $collection = collect(explode(',',$user->id_gruppo));
          $collection->push($publisher->id);
          $user->id_gruppo = $collection->implode(',');
          $user->save();
        }
        $query->delete();
      }
  }

  public function leaveGroup(Request $request)
  {
      $publisher = Editori::find($request->id);
      if($publisher->direttore == Auth::user()->id && Auth::user()->hasMemberOf($publisher->id)) {
        $query = User::find(Auth::user()->id);
        $query->id_gruppo = collect(explode(',', $query->id_gruppo))->filter(function ($value, $key) use ($publisher) {
          return $value != "" && $value != $publisher->id;
        })->implode(',');
        $query->save();
        $publisher->componenti = collect(explode(',', $publisher->componenti))->filter(function ($value, $key) {
          return $value != "" && $value != Auth::user()->id;
        })->implode(',');
        $publisher->save();
        return Response::json(['success' => 'true']);
      } else {
        return Response::json(['success' => 'false', 'message' => 'Per abbandonare questa redazione devi prima nominare un nuovo capo redattore']);
      }
  }

  public function getSupportRequest(Request $request)
  {
      $obj = new \stdClass();
      $obj->from = "no-reply@sywrit.com";
      $obj->to = "support@sywrit.com";

      if(Auth::user()) {
        $obj->username = Auth::user()->name.' '.Auth::user()->surname;
        $obj->email = Auth::user()->email;
      } else {
        $obj->username = "Ospite";
        $obj->email = $request->email;
      }

      if($request->selector == '1') {
        $obj->subject = 'Richiesta supporto';
      } elseif($request->selector == '2') {
        $obj->subject = 'Feeback';
      }

      $obj->message = $request->text;

      Mail::send(new SupportEmail($obj));

      return Response::json(['message' => 'Grazie per averci contattato.']);
  }

  public function getAuth(Request $request)
  {
      if(!$request->session()->get('redirectTo')) {
        $request->session()->put('redirectTo', $request->path);
      }
      $callback = $request->callback;
      return view('front.components.ajax.'. $callback);
  }

}
