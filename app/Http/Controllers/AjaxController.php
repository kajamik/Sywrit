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

class AjaxController extends Controller
{
  public function follow(Request $request)
  {
    if($request->ajax()){

      $query = Articoli::where('id',$request->id)->first();
      if(empty($query->id_gruppo)){
        $query = User::where('id',$query->autore)->first();
        if($query->id == \Auth::user()->id){
          return ;
        }
      }else{
        $query = Editori::where('id',$query->id_gruppo)->first();
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
        //
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

        $query3 = Editori::where('nome', 'like', $search. '%')
                ->limit(5)
                ->get();

          return view('front.pages.livesearch')->with(['users' => $query, 'articles' => $query2, 'pages' => $query3, 'search' => $search]);
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

}
