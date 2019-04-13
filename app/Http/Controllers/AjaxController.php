<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;

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
use App\Models\ArticleScore;

class AjaxController extends Controller
{

  public function __construct(Request $request)
  {
    if(!$request->ajax()){
      abort(404);
    }
  }

  /*public function follow(Request $request)
  {
    if($request->ajax()){
      if($request->input('q') == 'false'){
        $query = User::where('id',$request->id)->first();
        if($query->id == Auth::user()->id){
          return;
        }
      }else{
        $query = Editori::where('id',$request->id)->first();
      }

      try{
        $collection = collect(explode(',',$query->followers));
        $count = $query->followers_count;
        if($collection->some(Auth::user()->id)){
          $collection->splice($collection->search(Auth::user()->id),1);
          $query->followers_count -= 1;
          $count--;
        }else{
          $collection->push(Auth::user()->id);
          $query->followers_count += 1;
          $count++;
        }
        $query->followers = $collection->implode(',');
        $query->save();
        return Response::json(['result' => $collection->some(Auth::user()->id), 'counter' => $count]);
      }catch(ErrorException $error){
        \Debugbar::info("follow: ".$error);
      }
    }
  }*/

  public function rate(Request $request)
  {
    if($request->ajax()){

      $article_id = $request->id;

      $score = ArticleScore::where('user_id', Auth::user()->id)->where('article_id', $article_id)->count();

      if($request->rate_value <= 0) {
        $rating_value = 1;
      } elseif($request->rate_value > 5) {
        $rating_value = 5;
      } else {
        $rating_value = $request->rate_value;
      }

      try{
        $article = Articoli::where('id', $article_id)->first();

        if(Auth::user()->id != $article->id_autore && !$score && !empty($article)){

          $score = new ArticleScore();
          $score->user_id = Auth::user()->id;
          $score->article_id = $article->id;
          $score->score = $rating_value;
          $score->save();

          if($article->id_gruppo != NULL) {
            $editore = \DB::table('editori')->where('id', $query->id_gruppo)->first();
            $components = collect(explode(',',$editore->componenti))->filter(function ($value, $key) {
              return $value != "";
            });
            foreach($components as $value) {
              $noty = new Notifications();
              $noty->sender_id = Auth::user()->id;
              $noty->target_id = $value;
              $noty->content_id = $query->id;
              $noty->text = $rating_value;
              $noty->type = '2';
              $noty->marked = '0';
              $noty->save();
            }
          } else {
              $noty = new Notifications();
              $noty->sender_id = Auth::user()->id;
              $noty->target_id = $article->id_autore;
              $noty->content_id = $query->id;
              $noty->text = $rating_value;
              $noty->type = '2';
              $noty->marked = '0';
              $noty->save();
          }
        }
        return Response::json($rating_value);
      }catch(ErrorException $error){
        //
      }
    }
  }

  /*public function history(Request $request)
  {
    if($request->ajax()){
      $query = ArticleHistory::where('article_id', $request->id)
              ->select(\DB::raw('concat(substring_index(no_tags_text, " ", 25),"...") as no_tags_text'),'token','created_at')
              ->get();

      return Response::json($query);
    }
  }*/

  public function SearchLiveData(Request $request)
  {
    if($request->ajax()){

        $search = $request->q;

      if(!is_null($search)){
        $query = User::where(\DB::raw("concat(nome, ' ', cognome)"), 'like', '%'.$search.'%')
              ->limit(5)
              ->get();

        $query2 = Articoli::where('titolo', 'like', '%'. $search .'%')
                ->limit(5)
                ->get();

        $query3 = Articoli::where('tags', 'like', '%'. $search. '%')
                  ->limit(5)
                  ->get();

        $query4 = Editori::where('nome', 'like', '%'. $search. '%')
                ->limit(5)
                ->get();

          $query = $query->merge($query2)
                          ->merge($query3)
                          ->merge($query4);

          return view('front.pages.livesearch')->with(['query' => $query, 'key' => $search]);
        }
      }
  }

  public function getStateNotifications(Request $request)
  {
    if($request->ajax()){
      $count = $request->msg_count;
      $noty = \DB::table('notifications')->where('target_id', Auth::user()->id)->where('marked','0');

      if($noty->count() > $count){
        $queries = array();
        foreach($noty->get() as $value){
          array_push($queries, \DB::table('articoli')->join('utenti', 'articoli.id_autore', '=', 'utenti.id')->addSelect('articoli.titolo as titolo','articoli.slug as article_slug','utenti.nome as user_name','utenti.cognome as user_surname')->where('articoli.id', $value->content_id)->first());
        }
        return Response::json(['count' => $noty->count(), 'query' => $queries]);
      }
    }
  }

  public function getNotifications(Request $request)
  {
    if($request->ajax()){
      $LIMIT = 3;
      $query = Notifications::where('target_id',Auth::user()->id)->orderBy('created_at','desc')->get();
      if(Auth::user()->notifications_count > 0){
        $user = User::find(Auth::user()->id);
        $user->notifications_count = '0';
        $user->save();
      }
      return view('front.pages.livenotifications')->with(['query' => $query]);
    }
  }

  public function deleteAllNotifications(Request $request)
  {
    if($request->ajax()) {
      $notifiche = Notifications::where('target_id',Auth::user()->id);
      $notifiche->delete();
    }
  }

  /*** Commenti ***/

  public function getStateComments(Request $request)
  {
    if($request->ajax()) {
      $state = true;
      $query = ArticleComments::where('article_id',$request->id)->count();
      if($request->count != $query){
        $state = false;
      }
      return Response::json(['state' => $state]);
    }
  }

  public function loadComments(Request $request)
  {
    if($request->ajax()) {
      $PAGE = $request->q;
      $LIMIT = 6;
      if($PAGE == 1){
        $comments = ArticleComments::take($LIMIT)->where('article_id', $request->id)->orderBy('created_at','desc')->get();
      }else{
        $comments = ArticleComments::skip($LIMIT * ($PAGE - 1))->take($LIMIT)->where('article_id', $request->id)->orderBy('created_at','desc')->get();
      }
      return view('front.components.ajax.loadComments')->with(['query' => $comments]);
    }
  }

  public function postComments(Request $request)
  {
    if($request->ajax()) {
      $post = $request->post;
      if(!empty($post)){
        $query = new ArticleComments();
        $query->user_id = Auth::user()->id;
        $query->text = $post;
        $query->article_id = $request->id;
        $query->save();

        if($query->id_gruppo != NULL) {
          $editore = \DB::table('editori')->where('id', $query->id_gruppo)->first();
          $components = collect(explode(',',$editore->componenti))->filter(function ($value, $key) {
            return $value != "";
          });
          foreach($components as $value) {
            $noty = new Notifications();
            $noty->sender_id = Auth::user()->id;
            $noty->target_id = $value;
            $noty->content_id = $query->id;
            $noty->text = '';
            $noty->type = '3';
            $noty->marked = '0';
            $noty->save();
          }
        } else {
            $noty = new Notifications();
            $noty->sender_id = Auth::user()->id;
            $noty->target_id = $query->id_autore;
            $noty->content_id = $query->id;
            $noty->text = '';
            $noty->type = '3';
            $noty->marked = '0';
            $noty->save();
        }
        return view('front.components.ajax.uploadComment')->with(['query' => $query]);
      }
    }
  }

  /*** Risposte ***/

  public function loadAnswers(Request $request)
  {
    if($request->ajax()) {
      $PAGE = $request->q;
      $LIMIT = 6;
      if($PAGE == 1){
        $answers = AnswerComments::take($LIMIT)->where('comment_id', $request->id)->orderBy('created_at','desc')->get();
      }else{
        $answers = AnswerComments::skip($LIMIT * ($PAGE - 1))->take($LIMIT)->where('comment_id', $request->id)->orderBy('created_at','desc')->get();
      }
      return view('front.components.ajax.loadCommentAnswers')->with(['query' => $answers]);
    }
  }

  public function postAnswers(Request $request)
  {
    if($request->ajax()) {
      $post = $request->post;
      if(!empty($post)){
        $query = new AnswerComments();
        $query->user_id = Auth::user()->id;
        $query->text = $post;
        $query->comment_id = $request->id;
        $query->save();
        return view('front.components.ajax.uploadAnswers')->with(['query' => $query]);
      }
    }
  }

  /*** Publishers ***/


    public function inviteGroup(Request $request)
    {
      $publisher_id = $request->selector;
      $collection = collect(explode(',', Auth::user()->id_gruppo));

      $query = User::find($request->user_id);
      if(Auth::user()->hasFoundedGroup() && $collection->some($publisher_id)) {
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
          return Response::json(['message' => 'Richiesta di collaborazione inviata!!']);
        } else {
          return Response::json(['message' => 'Questo utente fa già parte di questa redazione']);
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
      }
  }

  public function leaveGroup(Request $request)
  {
      $query = Editori::where('id', Auth::user()->id_gruppo)->first();
      $slug = $query->slug;

      if($query->direttore != Auth::user()->id) {
        try{
          $collection = collect(explode(',', $query->componenti));
          $collection->splice(Auth::user()->id);
          $query->componenti = $collection->implode(',');
          $query->save();
          $user = User::find(Auth::user()->id);
          $collection = collect(explode(',', $user->id_gruppo));
          $collection->splice($query->id);
          $user->id_gruppo = $collection->implode(',');
          $user->save();
          return Response::json(['message' => 'Hai abbandonato questa redazione']);
        }catch(ErrorException $error){
          //
        }
      } else {
        return Response::json(['message' => 'Per lasciare questa redazione devi prima cedere la carica da capo redattore']);
      }

    }

}
