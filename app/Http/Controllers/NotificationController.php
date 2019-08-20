<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;

use App\Events\UserNotification;

use Response;
use Auth;

use App\Models\User;
use App\Models\Notification;
use App\Models\Articoli;

class NotificationController extends Controller
{
    /*
        check new records into query
    */
    public function check(Request $request)
    {
        if(Auth::user()->notifications_to_read == 'true') {
          return $this->get($request);
        }
    }
    /*
        get last records
    */
    public function get(Request $request)
    {
        $ONCE_LIMIT_CALLED = 5; // 0 no limits
        $query = Notification::where('target_id', Auth::user()->id)->where('read', '0')->orderBy('created_at','desc');
        if($ONCE_LIMIT_CALLED > 0) {
          $query->limit($ONCE_LIMIT_CALLED);
        }
        $posts = array();
        foreach($query->get() as $i => $value) {
          array_push($posts,
            \DB::table('articoli')->join('utenti', 'articoli.id_autore', '=', 'utenti.id')
                      ->addSelect('articoli.titolo as article_title', 'articoli.copertina as article_img', 'articoli.slug as article_slug', 'utenti.name as user_name', 'utenti.surname as user_surname')
                      ->where('articoli.id', $value->content_id)
                      ->first()
          );
          if(!$posts[$i]->article_img) {
            $posts[$i]->article_img = asset('upload/no-image.jpg');
          }
        }
        $user = User::find(Auth::user()->id);
        $user->notifications_to_read = 'false';
        $user->save();
        return Response::json(['f' => $posts, 'unseen_notification' => count($posts)]);
    }

    public function put(Request $request)
    {
        //
    }
}
