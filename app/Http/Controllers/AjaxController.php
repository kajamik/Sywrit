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

class AjaxController extends Controller
{

  public function __construct(Request $request)
  {
    if(!$request->ajax()){
      abort(404);
    }
  }

  public function follow(Request $request)
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
  }

  public function rate(Request $request)
  {
    if($request->ajax()){

      $query = Articoli::where('id', $request->id)->first();

      if($request->rate_value <= 0) {
        $rating_value = 1;
      } elseif($request->rate_value > 5) {
        $rating_value = 5;
      } else {
        $rating_value = $request->rate_value;
      }

      try{
        $collection = collect(explode(',',$query->rated));

        if(Auth::user()->id != $query->id_autore && !$collection->some(Auth::user()->id)){
          $collection->push(Auth::user()->id);
          $query->rating_count += 1;

          $query->rating = ($query->rating + $rating_value) / $query->rating_count;
          $query->rated = $collection->implode(',');
          $query->save();
        }
        return Response::json(['result' => $collection->some(Auth::user()->id)]);
      }catch(ErrorException $error){
        //
      }
    }
  }

  public function SearchLiveData(Request $request)
  {
    if($request->ajax()){

        $search = $request->q;

      if(!is_null($search)){
        $query = User::where(\DB::raw("concat(nome, cognome)"), 'like', '%'.$search.'%')
              ->limit(5)
              ->get();

        $query2 = Articoli::where('titolo', 'like', $search .'%')
                ->limit(5)
                ->get();

        $query3 = Articoli::where('tags', 'like', $search. '%')
                  ->limit(5)
                  ->get();

        $query4 = Editori::where('nome', 'like', $search. '%')
                ->limit(5)
                ->get();

          $query = $query->merge($query2)
                          ->merge($query3)
                          ->merge($query4);

          return view('front.pages.livesearch')->with(['query' => $query, 'key' => $search]);
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
        $message = new PublisherRequest();
        $message->user_id = Auth::user()->id;
        $message->target_id = $query->id;
        $message->publisher_id = $publisher_id;
        $message->token = Str::random(32);
        $message->scadenza = null;
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
      $query = Editori::where('id',Auth::user()->id_gruppo)->first();
      $slug = $query->slug;

      if($query->direttore == Auth::user()->id) {
        \Session::flash('alert','Non puoi lasciare il gruppo se sei il capo redattore');
      } else {
        try{
          $collection = collect(explode(',',$query->componenti));
          $collection->splice($collection->search(Auth::user()->id),1);
          $query->componenti = $collection->implode(',');
          $query->save();
          $user = User::find(Auth::user()->id);
          $user->id_gruppo = null;
          $user->save();
        }catch(ErrorException $error){
          //
        }
      }

    }

}
