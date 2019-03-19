<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;

use Auth;

// Models
use App\Models\Editori;
use App\Models\Articoli;
use App\Models\User;
use App\Models\Notifications;
use App\Models\ArticleComments;

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
        if($query->id == \Auth::user()->id){
          return;
        }
      }else{
        $query = Editori::where('id',$request->id)->first();
      }

      try{
        $collection = collect(explode(',',$query->followers));
        $count = $query->followers_count;
        if($collection->some(\Auth::user()->id)){
          $collection->splice($collection->search(\Auth::user()->id),1);
          $query->followers_count -= 1;
          $count--;
        }else{
          $collection->push(\Auth::user()->id);
          $query->followers_count += 1;
          $count++;
        }
        $query->followers = $collection->implode(',');
        $query->save();
        return Response::json(['result' => $collection->some(\Auth::user()->id), 'counter' => $count]);
      }catch(ErrorException $error){
        \Debugbar::info("follow: ".$error);
      }
    }
  }

  public function like(Request $request)
  {
    if($request->ajax()){

      $query = Articoli::where('id',$request->id)->first();
      try{
        $collection = collect(explode(',',$query->likes));
        $count = $query->likes_count;
        if($collection->some(\Auth::user()->id)){
          $collection->splice($collection->search(\Auth::user()->id),1);
          $query->likes_count -= 1;
          $count--;
        }else{
          $collection->push(\Auth::user()->id);
          $query->likes_count += 1;
          $count++;
        }
        $query->likes = $collection->implode(',');
        $query->save();
        return Response::json(['result' => $collection->some(\Auth::user()->id), 'counter' => $count]);
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
        $query = User::where('nome', 'like', $search .'%')
              ->orWhere('cognome', 'like', $search .'%')
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
        $comments = ArticleComments::take($LIMIT)->where('article_id',$request->id)->orderBy('created_at','desc')->get();
      }else{
        $comments = ArticleComments::skip($LIMIT * ($PAGE - 1))->take($LIMIT)->where('article_id',$request->id)->orderBy('created_at','desc')->get();
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

}
