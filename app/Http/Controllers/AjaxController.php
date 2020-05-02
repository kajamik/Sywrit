<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use App\Mail\SupportEmail;

use Response;
use Auth;

// debug
use Log;

// Models
use App\Models\Editori;
use App\Models\Articoli;
use App\Models\DraftArticle;
use App\Models\User;
use App\Models\Notifications;
use App\Models\PublisherRequest;
use App\Models\ArticleComments;
use App\Models\AnswerComments;
use App\Models\ArticleLikes;
use App\Models\SocialService;
// Groups
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupConversation;
use App\Models\GroupConversationAnswer;
use App\Models\GroupJoinRequest;

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

  public function autoSaving(Request $request)
  {
      $document__text = $request->text;
      $request = explode('&', $request->form_request);
      $form = array();
      foreach($request as $value) {
          $tmp = explode('=', $value);
          $form[$tmp[0]] = $tmp[1];
      }
      $request = (object) $form;
      //--
      $current_session_key = \Session::get('current_article_key');
      $session_id = \Session::get('draft_article_id');
      //--
      if(isset($current_session_key) && isset($session_id)) {
        $query = DraftArticle::whereNull('scheduled_at')->where('id_autore', Auth::user()->id)->where('id', $session_id)->first();
        $query->topic_id = is_numeric($request->_ct_sel_) ? $request->_ct_sel_ : NULL;
        $query->titolo = isset($request->document__title) ? urldecode($request->document__title) : "Documento senza nome";
        $query->tags = str_slug(urldecode($request->tags), ',');
        $query->testo = $document__text;
        $query->copertina = isset($request->image) ? $request->image : NULL;
        $query->license = is_numeric($request->_m_sel) ? $request->_m_sel : 1;
        $query->save();
      } else {
        $query = DraftArticle::create([
          'topic_id' => is_numeric($request->_ct_sel_) ? $request->_ct_sel_ : NULL,
          'titolo' => isset($request->document__title) ? urldecode($request->document__title) : "Documento senza nome",
          'tags' => str_slug(urldecode($request->tags), ','),
          'testo' => $document__text,
          'copertina' => isset($request->image) ? $request->image : NULL,
          'id_gruppo' => NULL,
          'id_autore' => Auth::user()->id,
          'license' => is_numeric($request->_m_sel) ? $request->_m_sel : 1,
          'scheduled_at' => isset($request->datetime) ? $request->datetime : NULL
        ]);
        \Session::put('current_article_key', str_random(64));
        \Session::put('draft_article_id', $query->id);
        \Debugbar::info("Bozza salvata");
      }
  }

  public function rate(Request $request)
  {
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
        array_push($queries, \DB::table('articoli')->join('users', 'articoli.id_autore', '=', 'users.id')->addSelect('articoli.titolo as titolo','articoli.slug as article_slug','users.name as user_name','users.surname as user_surname')->where('articoli.id', $value->content_id)->first());
      }
      return Response::json(['count' => $noty->count(), 'query' => $queries]);
    }
  }

  public function getNotifications(Request $request)
  {
    $LIMIT = 3;
    $query = Notifications::where('target_id', Auth::user()->id)->orderBy('created_at','desc')->get();
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

  public function getUserThumbnail(Request $request)
  {
      switch($request->h) {
        case 'group':
          $query = Group::where('id', $request->id)->first();
          break;
        case 'profile':
          $query = User::where('id', $request->id)->first();
          break;
      }

      return view('front.components.thumbnail', compact('query'))->with('type', $request->h);
  }

  public function getWebData(Request $request)
  {

      $domain = explode('.', preg_split('/[a-z:]*\/\/[ww*.*]*|\/(.*)/', $request->url)[1])[0];

        function getDOMDocument($html, $request) {
            $dom = new \DOMDocument();
            @$dom->loadHTML($html);

            function searchNode($root, $tagName, $name = null, $content = null) {
                if($name) {
                  foreach($root->getElementsByTagName($tagName) as $value) {
                    if($value->getAttribute($name) == $content) {
                      return $value->getAttribute('content');
                    }
                  }
                } else {
                    return $root->getElementsByTagName($tagName)->item(0)->nodeValue;
                }
            }

            function searchFirstImage($root) {
                /*foreach($root->getElementsByTagName('img') as $value) {
                    return $value->getAttribute('src');
                }*/
                return $root->getElementsByTagName('img')[0];
            }

            return [
              'title' => searchNode($dom, 'title'),
              'domain' => ucfirst(preg_split('/[a-z:]*\/\/[ww*.*]*|\/(.*)/', $request->url)[1]),
              'url' => searchNode($dom, 'meta', 'property', 'og:url') ? searchNode($dom, 'meta', 'property', 'og:url') : $request->url,
              'description' => searchNode($dom, 'meta', 'name', 'description') ? searchNode($dom, 'meta', 'name', 'description') : searchNode($dom, 'title'),
              'image' => searchNode($dom, 'meta', 'property', 'og:image') ? searchNode($dom, 'meta', 'property', 'og:image') : searchFirstImage($dom),
              'author' => searchNode($dom, 'meta', 'property', 'article:author'),
              'published_time' => searchNode($dom, 'meta', 'property', 'article:published_time'),
            ];
        }

        try {
          $cURL = curl_init();
          curl_setopt($cURL, CURLOPT_URL, $request->url);
          curl_setopt($cURL, CURLOPT_HEADER, false);
          curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true);
          $result = curl_exec($cURL);
          $contentType = curl_getinfo($cURL, CURLINFO_CONTENT_TYPE);
          $mimetype = explode('/', $contentType)[0];
          curl_close($cURL);

          if($mimetype == 'image') {
            header('Content-Type: '. $contentType);
            echo $result;
            return;
          } elseif($mimetype == 'text') {
              if($domain == 'youtube') {
                  $type = preg_split('/youtube.[a-zA-Z]*\/|[?]/', $request->url)[1];
                  if($type == 'watch') {
                    $privateKey = 'AIzaSyBvxoM_rcPn0Xt4n0T3YliNT5P0UoDuVwU';
                    $id = explode('v=', $request->url)[1];
                    $part = 'snippet';
                    $link = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$id.'&key='.$privateKey.'&part=snippet'));
                    $meta = [
                      'title' => $link->items[0]->snippet->title,
                      'domain' => ucfirst(preg_split('/[a-z:]*\/\/[ww*.*]*|\/(.*)/', $request->url)[1]),
                      'url' => $request->url,
                      'description' => $link->items[0]->snippet->description,
                      'image' => $link->items[0]->snippet->thumbnails->high->url,
                    ];
                  } else {
                    $meta = getDOMDocument($result, $request);
                    $meta['title'] = $meta['description'];
                  }
              } else {
                $meta = getDOMDocument($result, $request);
              }
          }
        } catch(Exception $err) {
          return;
        }
      return view('front.components.ajax.get_info')->with(['url' => $request->url, 'node' => $meta]);
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
    if(!Auth::user()->comment_block) {
      $post = $request->post;
      $query = Articoli::where('id', $request->id)->first();

      if(!empty($post)){

        $query2 = ArticleComments::create([
          'user_id' => Auth::user()->id,
          'text' => $post,
          'article_id' => $query->id
        ]);

        $article = Articoli::find($query2->article_id);

        return view('front.components.ajax.uploadComment')->with(['post' => $query2]);
      }
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
    if(!Auth::user()->comment_block) {
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
  }

  /*** Groups ***/

  public function loadGroupMessage(Request $request)
  {
      $current_page = ($request->q) ? $request->q : 1;

      if(!$request->answer) {
        $query = Group::where('id', $request->id)->first();
      } else {
        $query2 = GroupConversation::where('id', $request->id)->orderBy('created_at','asc')->first();
        $query = Group::where('id', $query2->group_id)->first();
      }

      if((Auth::user() && Auth::user()->hasMemberOf($query->id)) || $query->public) {
        if(!$request->answer) {
          $LIMIT = 6;
          $query2 = GroupConversation::leftJoin('group_article', function($join){
                                        $join->on('group_conversation.article_id', '=', 'group_article.id');
                                      })
                                      ->addSelect('group_article.id as article_id', 'group_article.title as article_title', 'group_article.text as article_text', 'group_article.cover as cover',
                                                  'group_conversation.id as id', 'group_conversation.user_id as user_id', 'group_conversation.article_id as article_id', 'group_conversation.text as text', 'group_conversation.created_at as created_at')
                                      ->where('group_conversation.group_id', $query->id)
                                      ->orderBy('created_at','desc')
                                      ->skip($LIMIT * ($current_page-1))
                                      ->take($LIMIT)
                                      ->get();
          return view('front.components.ajax.group.loadConversation')->with(['group' => $query, 'query' => $query2]);
        } else {
          $LIMIT = 1;
          $query3 = GroupConversationAnswer::where('conversation_id', $request->id)->orderBy('created_at','desc');
          $count = $query3->count();
          $query3 = $query3->skip($LIMIT * ($current_page-1))->take($LIMIT)->get();;
          return view('front.components.ajax.group.loadConversationAnswer')->with(['group' => $query, 'chat' => $query2,'query' => $query3, 'count' => $count]);
        }
      }
  }

  public function loadMembers(Request $request)
  {
      $current_page = ($request->q) ? $request->q : 2;
      $query = Group::find($request->id);

      return view('front.components.ajax.group.get_members', compact('query', 'current_page'));
  }

  public function joinGroupRequest(Request $request)
  {
      $query = Group::where('id', $request->id)->first();

      if((Auth::user() && !Auth::user()->hasMemberOf($query->id))) {

        if($query->public) {
          GroupMember::create([
            'group_id' => $query->id,
            'user_id' => Auth::user()->id,
            'permission_level' => 'User'
          ]);
          $user = User::where('id', Auth::user()->id)->first();
          if(!empty($user->id_gruppo)) {
            $user->id_gruppo = $query->id_gruppo. ",". $query->id;
          } else {
            $user->id_gruppo = $query->id;
          }
          $user->save();
        } else {
          $join = new GroupJoinRequest();
          $join->group_id = $query->id;
          $join->user_id = Auth::user()->id;
          $join->save();
          return Response::json(['message' => 'Richiesta di iscrizione al gruppo inviata.']);
        }
      }
  }

  public function joinGroupResponse(Request $request)
  {
      $req = GroupJoinRequest::where('id', $request->id)->first();

      $query = GroupMember::where('user_id', Auth::user()->id)
                          ->where('group_id', $req->group_id)
                          ->first();

      if(Auth::user() && ($query->permission_level == 'Administrator' || $query->permission_level == 'Moderator')) {
          if($request->res) {
            $user = User::where('id', $req->user_id)->first();
              if(!empty($user->id_gruppo)) {
                $user->id_gruppo = $query->id_gruppo. ",". $req->group_id;
              } else {
                $user->id_gruppo = $req->group_id;
              }
            $user->save();
            GroupMember::create([
              'group_id' => $req->group_id,
              'user_id' => $user->id,
              'permission_level' => 'User'
            ]);
            $req->delete();
          } else {
            $req->delete();
          }
      }
  }

  public function scheduleArticle(Request $request)
  {
      $rDate = $request->date;
      $rTime = $request->time;

      if(!isset($request->draft) || (isset($request->draft) && !$request->draft)) { // Verifico che non sia un articolo già salvato
        if(!isset($request->a_id)) {
          return view('front.components.ajax.schedule')->with(['date' => $rDate, 'time' => $rTime]);
        } else { // Modifico la data dell'articolo già programmato
          $query = DraftArticle::whereNotNull('scheduled_at')->whereNull('id_gruppo')->where('id_autore', Auth::user()->id)->where('id', $request->a_id)->first();
          $query->scheduled_at = $rDate. ' '. $rTime;
          $query->save();
          return Response::json(['date' => \Carbon\Carbon::parse($rDate)->translatedFormat('l j F Y'), 'time' => $rTime]);
        }
      } else { // Articolo salvato
        $query = DraftArticle::whereNull('scheduled_at')->whereNull('id_gruppo')->where('id_autore', Auth::user()->id)->where('id', $request->a_id)->first();
        $query->scheduled_at = $rDate. ' '. $rTime;
        $query->save();
        return Response::json('location.replace("/articles/schedule/view/'. $query->id. '")');
      }
  }

  public function removeSchedule(Request $request)
  {
      $query = DraftArticle::whereNotNull('scheduled_at')->whereNull('id_gruppo')->where('id_autore', Auth::user()->id)->where('id', $request->id)->first();

      $query->scheduled_at = NULL;

      $query->save();

      return Response::json('location.replace("/articles/draft/view/'. $query->id. '")');
  }

  public function sendGroupMessage(Request $request)
  {
      if(!$request->reply) {
        $query = Group::where('id', $request->id)->first();
        $group_id = $query->id;
      } else {
        $query = GroupConversation::where('id', $request->id)->first();
        $group_id = $query->group_id;
      }
      if(!empty($request->post) && Auth::user()->hasMemberOf($group_id)) {

        if(!$request->reply) {
          $query2 = GroupConversation::create([
            'group_id' => $query->id,
            'user_id' => Auth::user()->id,
            'text' => $request->post,
          ]);
          return view('front.components.ajax.group.uploadConversation')->with(['post' => $query2]);
        } else {
          $query2 = GroupConversationAnswer::create([
            'conversation_id' => $request->id,
            'user_id' => Auth::user()->id,
            'text' => $request->post
          ]);
          return view('front.components.ajax.group.uploadConversationAnswer')->with(['post' => $query2]);
        }
      }
  }

  // post?id=&action={delete, report}
  public function postAction(Request $request)
  {
      try {
        $post_id = $request->id;
        switch($request->action) {
          case 'delete':
            $conversation = GroupConversation::find($request->id);
            if(!empty($conversation->article_id)) {
              GroupArticle::find($conversation->article->id)->delete();
            }
            $conversation->delete();
          break;
        }
      } catch(Exception $e) {
          return \Debugbar::info('Impossibile cancellare il post');
      }
  }

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
        $obj->subject = 'Feedback';
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

  public function getAddSocialAddress()
  {
      $apps = SocialService::orderBy('name', 'asc')->get();
      return view('front.components.social_links', compact('apps'));
  }

}
