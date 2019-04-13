<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

// Models
use App\Models\User;
use App\Models\Articoli;
use App\Models\ArticleHistory;
use App\Models\ArticleCategory;
use App\Models\Editori;
use App\Models\BackupArticlesImages;
use App\Models\Notifications;
//----------

use Carbon\Carbon;

use DB;
use Storage;
use Image;
use File;
use Auth;

// SEO
use SEOMeta;
use OpenGraph;
use Twitter;

class FrontController extends Controller
{

    public function listGroups($user)
    {
      if($user->getPublishersInfo()) {
        $group = array();
        foreach($user->getPublishersInfo() as $value) {
          $group[] = $value;
        }

        return \View::share('group', $group);
      }
      return false;
    }

    public function index(Request $request)
    {
        $INDEX_LIMIT = 9;
        $current_page = ($request->page) ? $request->page : 1;

        $articoli = Articoli::join('utenti', 'articoli.id_autore', '=', 'utenti.id')
                    ->addSelect('utenti.slug as user_slug', 'utenti.nome as user_name', 'utenti.cognome as user_surname',
                                'articoli.titolo as article_title', 'articoli.slug as article_slug', 'articoli.testo as article_text', 'articoli.copertina as copertina', 'articoli.created_at as created_at')
                    ->where('status','1')
                    ->orderBy('published_at','desc')
                    ->skip($INDEX_LIMIT * ($current_page-1))
                    ->take($INDEX_LIMIT)
                    ->get();

        $categorie = ArticleCategory::get();

          if($request->ajax()){
            if(count($articoli)){
              return ['posts' => view('front.components.ajax.loadAll')->with(compact('articoli'))->render()];
            }
          }

      return view('front.pages.welcome',compact('articoli','categorie'));
    }

    // PROFILE
    public function getProfile($slug,Request $request)
    {
      //$follow = '';
      $query = User::where('slug',$slug)->first();
      if(empty($query)){
        return $this->getPublisherIndex($slug, $request);
      }
      /*if(\Auth::user() && !empty($query)){
          $follow = in_array(\Auth::user()->id,explode(',',$query->followers));
      }*/
      $query2 = \App\Models\Articoli::where('status','1')->where('id_autore',$query->id);
      $count = $query2->count();
      $articoli = $query2->take(12)->orderBy('created_at','desc')->get();

      $score = \DB::table('article_score')->where('article_id', $query->id);
      $scoring = \DB::table('article_score')->where('user_id', Auth::user()->id)->where('article_id', $query->id)->count();

      if($count){
        if($request->ajax()){
          $articoli = $query2->skip(($request->page-1)*12)->take(12)->get();
            return ['posts' => view('front.components.ajax.loadArticles')->with(compact('articoli'))->render()];
        }
      }

      $this->listGroups($query);

      return view('front.pages.profile.index',compact('query','query2','count','articoli','score','scoring'));
    }

    public function getAbout($slug)
    {
      $query = User::where('slug',$slug)->first();

      if(empty($query))
        return $this->getPublisherAbout($slug);

      $query2 = \App\Models\Articoli::where('status','1')->where('id_autore',$query->id);
      $count = $query2->count();

        /*$followers = collect(explode(',',$query->followers))->filter(function ($value, $key) {
          return $value != "";
        });

        if(Auth::user() && !empty($query)){
          $follow = $followers->some(\Auth::user()->id);
        }*/

        $this->listGroups($query);

      return view('front.pages.profile.about',compact('query','query2','count'));
    }

    public function getPrivateArchive($slug)
    { // http://127,0.0.1:8000/{slug}/archive
      if($slug == Auth::user()->slug){
        $query = Articoli::whereNull('id_gruppo')->where('id_autore', Auth::user()->id)->where('status','0')->get();
        $this->listGroups(Auth::user());
        return view('front.pages.profile.archive',compact('query'));
      }else{
        return redirect($slug);
      }
    }

    /*****************/

    // GROUP
    public function getNewPublisher()
    {
        return view('front.pages.group.create');
    }

    public function getPublisherIndex($slug,Request $request)
    {
      $query = Editori::where('slug',$slug)->firstOrFail();

      /*if( empty($query) || (!$query->accesso && (Auth::guest() || Auth::user()->id != $query->direttore)) )
          abort(404);*/

        $publisher = array();
        //$followers = array();
        //$follow = false;

        if($request->ajax()){
            $articoli = Articoli::where('id_gruppo',$query->id)->skip(($request->page-1)*12)->take(12)->get();
            return ['posts' => view('front.components.ajax.loadArticles')->with(compact('articoli'))->render()];
        }

        /*$followers = explode(',',$query->followers);

        if(\Auth::user() && !empty($query)){
          $follow = in_array(\Auth::user()->id,$followers);
        }*/

      return view('front.pages.group.index',compact('query','publisher'));
    }

    public function getPublisherAbout($slug)
    {
      $query = User::where('slug',$slug)->first();

      if(empty($query))
        $query = Editori::where('slug',$slug)->first();

      if(!$query->accesso && (Auth::guest() || Auth::user()->id != $query->direttore))
        abort(404);

        /*$followers = collect(explode(',',$query->followers))->filter(function ($value, $key) {
          return $value != "";
        });*/

        $components = collect(explode(',',$query->componenti))->filter(function ($value, $key) {
          return $value != "";
        });

        /*if(Auth::user() && !empty($query)){
          $follow = $followers->some(Auth::user()->id);
        }*/

      return view('front.pages.group.about',compact('query','components'));
    }

    public function getPublisherArchive($slug)
    { // http://127,0.0.1:8000/{slug}/archive
      if($slug == Auth::user()->slug){
        $query = Articoli::whereNull('id_gruppo')->where('id_autore', Auth::user()->id)->where('status','0')->get();
        $this->listGroups(Auth::user());
        return view('front.pages.profile.archive',compact('query'));
      }else{
        return redirect($slug);
      }
    }

    public function getPublisherSettings($slug,$tab = null,Request $request)
    {
      $query = Editori::where('slug',$slug)->first();

      if($query->direttore != \Auth::user()->id)
        return redirect($slug);

      if(!$tab)
        return redirect($slug.'/settings/edit');

      return view('front.pages.group.settings',compact('query','tab'));
    }

    /*****************/
    /**** ARTICLE ****/
    /*****************/

    public function getWrite(Request $request)
    {
      if(!$request->_topic){
        $categories = \DB::table('article_category')->orderBy('name', 'asc')->get();
      } else {
        $categories = \DB::table('article_category')->where('slug', $request->_topic)->first();
      }
      $this->listGroups(Auth::user());
      return view('front.pages.new_post',compact('categories'));
    }

    public function getArticle($slug)
    {
      $query = Articoli::where('slug', $slug)->first();

      $like = false;
      $options = false;

      if( Auth::user() ){
        $collection = collect(explode(',', Auth::user()->id_gruppo));
        $collection = collect(explode(',', $query->likes));

        if($collection->some(\Auth::user()->id)){
          $like = true;
        }

        $options = true;
      }

      $tags = explode(',',$query->tags);
      $date = Carbon::parse($query->created_at)->formatLocalized('%A %d %B %Y');
      $time = Carbon::parse($query->created_at)->format('H:i');

      $score = \DB::table('article_score')->where('article_id', $query->id);
      $scoring = \DB::table('article_score')->where('user_id', Auth::user()->id)->where('article_id', $query->id)->count();
      $hasRate = ($scoring > 0);

      return view('front.pages.read',compact('query','date','time','tags','options','like','score','scoring','hasRate'));
    }

    public function getArticleEdit($id)
    {
      $query = Articoli::find($id);
      if((Auth::user()->id_gruppo > 0 && $query->id_gruppo == Auth::user()->id_gruppo) || (Auth::user()->id == $query->id_autore)){
        return view('front.pages.edit_post',compact('query'));
      }else{
        return redirect('read/'.$query->slug);
      }
    }

    public function getArticleArchive(Request $request)
    {
      $query = Articoli::where('slug', $request->url)->first();
      $query2 = ArticleHistory::where('article_id', $query->id)->where('token', $request->token_id)->first();

      $like = false;

      if( Auth::user() ){
        $collection = collect(explode(',', Auth::user()->id_gruppo));
        $collection = collect(explode(',', $query->likes));

        if($collection->some(\Auth::user()->id)){
          $like = true;
        }
      }

      $tags = explode(',',$query->tags);
      $date = Carbon::parse($query2->created_at)->formatLocalized('%A %d %B %Y');
      $time = Carbon::parse($query2->created_at)->format('H:i');

      return view('front.pages.article_archive', compact('query','query2','tags','date','time'));
    }

    /*************/

    public function getTopic($slug, Request $request)
    {
      $topic = ArticleCategory::where('slug', $slug)->first();

      $INDEX_LIMIT = 9;
      $current_page = ($request->page) ? $request->page : 1;

      $articoli = Articoli::join('utenti', 'articoli.id_autore', '=', 'utenti.id')
                  ->addSelect('utenti.slug as user_slug', 'utenti.nome as user_name', 'utenti.cognome as user_surname',
                              'articoli.titolo as article_title', 'articoli.slug as article_slug', 'articoli.testo as article_text', 'articoli.copertina as copertina', 'articoli.created_at as created_at')
                  ->where('topic_id', $topic->id)
                  ->where('status','1')
                  ->orderBy('published_at','desc')
                  ->skip($INDEX_LIMIT * ($current_page-1))
                  ->take($INDEX_LIMIT)
                  ->get();

      if($request->ajax()){
        if(count($articoli)){
          return ['posts' => view('front.components.ajax.loadAll')->with(compact('articoli'))->render()];
        }
      }

      return view('front.pages.topic', compact('topic', 'articoli'));
    }

    public function getNotifications()
    {
      $query = Notifications::where('target_id',Auth::user()->id)->orderBy('created_at','desc')->get();
      return view('front.pages.profile.notifications', compact('query'));
    }

    public function getSettings()
    {
      return view('front.pages.profile.settings');
    }

    public function getPages($slug, $slug2 = '')
    {
      if($slug2) {
        $slug2 = '/'.$slug2;
      }
      return view('front.pages.static.'.$slug.$slug2);
    }

}
