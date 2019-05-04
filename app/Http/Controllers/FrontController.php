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
    
    public function index(Request $request)
    {
        $INDEX_LIMIT = 9;
        $current_page = ($request->page) ? $request->page : 1;

        $articoli = Articoli::join('utenti', 'articoli.id_autore', '=', 'utenti.id')
                    ->leftJoin('editori', function($join){
                        $join->on('articoli.id_gruppo', '=', 'editori.id');
                      })
                    ->leftJoin('article_category', function($join){
                        $join->on('articoli.topic_id', '=', 'article_category.id');
                      })
                    ->addSelect('utenti.slug as user_slug', 'utenti.name as user_name', 'utenti.surname as user_surname', 'editori.name as publisher_name', 'editori.slug as publisher_slug',
                                'articoli.titolo as article_title', 'articoli.id_gruppo as id_editore', 'articoli.slug as article_slug', 'articoli.testo as article_text', 'articoli.copertina as copertina',
                                'articoli.published_at as published_at', 'articoli.created_at as created_at', 'article_category.id as topic_id', 'article_category.name as topic_name')
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

      return view('front.pages.welcome',compact('articoli'));
    }

    // PROFILE
    public function getProfile($slug, Request $request)
    {

      $query = User::where('slug',$slug)->first();

      if(empty($query)){
        return $this->getPublisherIndex($slug, $request);
      }

      $INDEX_LIMIT = 9;
      $current_page = ($request->page) ? $request->page : 1;

      $articoli = Articoli::leftJoin('article_category', function($join){
                      $join->on('articoli.topic_id', '=', 'article_category.id');
                    })
                    ->addSelect('articoli.titolo as article_title', 'articoli.slug as article_slug', 'articoli.testo as article_text', 'articoli.copertina as copertina',
                                'articoli.published_at as published_at', 'articoli.created_at as created_at', 'article_category.id as topic_id', 'article_category.name as topic_name')
                    ->where('id_autore', $query->id)
                    ->where('status','1')
                    ->orderBy('published_at', 'desc');

      $count = $articoli->count();

      $articoli = $articoli->skip($INDEX_LIMIT * ($current_page-1))->take($INDEX_LIMIT)->get();


      $score = \DB::table('article_score')->where('article_id', $query->id);

        if($request->ajax()){
          if($articoli->count()){
            return ['posts' => view('front.components.profile.loadArticles')->with(compact('articoli'))->render()];
          }
        }

      return view('front.pages.profile.index',compact('query','articoli','count','score'));
    }

    public function getAbout($slug)
    {
      $query = User::where('slug',$slug)->first();

      if(empty($query))
        return $this->getPublisherAbout($slug);

      $query2 = \App\Models\Articoli::where('status','1')->where('id_autore',$query->id);
      $count = $query2->count();

      return view('front.pages.profile.about',compact('query','query2','count'));
    }

    public function getPrivateArchive($slug)
    { // http://127,0.0.1:8000/{slug}/archive
      if($slug == Auth::user()->slug){
        $query = Articoli::whereNull('id_gruppo')->where('id_autore', Auth::user()->id)->where('status','0')->get();
        return view('front.pages.profile.archive',compact('query'));
      }else{
        // Redazione
        $query = Editori::where('slug', $slug)->firstOrFail();

        if(!$query->suspended && Auth::user()->hasMemberOf($query->id)){
          $query2 = Articoli::where('id_gruppo', $query->id)->where('status','0')->get();
          return view('front.pages.group.archive',compact('query','query2'));
        }else{
          return redirect($slug);
        }
      }
    }

    public function getAccountDelete()
    {
        $articoli = Articoli::whereNull('id_gruppo')->where('id_autore', Auth::user()->id);
        $feedback = \DB::table('article_comments')->where('user_id', Auth::user()->id)
                    ->union(\DB::table('answer_comments')->where('user_id', Auth::user()->id));
        return view('front.pages.profile.delete', compact('articoli','feedback'));
    }

    /*****************/

    // GROUP
    public function getNewPublisher()
    {
        return view('front.pages.group.create');
    }

    public function getPublisherIndex($slug, Request $request)
    {
      $query = Editori::where('slug',$slug)->firstOrFail();

      $INDEX_LIMIT = 9;
      $current_page = ($request->page) ? $request->page : 1;

      $publisher = array();

      $articoli = Articoli::join('utenti', 'articoli.id_autore', '=', 'utenti.id')
                  ->leftJoin('article_category', function($join){
                      $join->on('articoli.topic_id', '=', 'article_category.id');
                    })
                    ->addSelect('utenti.slug as user_slug', 'utenti.name as user_name', 'utenti.surname as user_surname',
                                'articoli.titolo as article_title', 'articoli.slug as article_slug', 'articoli.testo as article_text', 'articoli.copertina as copertina',
                                'articoli.published_at as published_at', 'articoli.created_at as created_at', 'article_category.id as topic_id', 'article_category.name as topic_name')
                    ->where('articoli.id_gruppo', $query->id)
                    ->where('status', '1')
                    ->orderBy('published_at', 'desc')
                    ->skip($INDEX_LIMIT * ($current_page-1))
                    ->take($INDEX_LIMIT)
                    ->get();

        if($request->ajax()){
          if($articoli->count()){
            return ['posts' => view('front.components.group.loadArticles')->with(compact('articoli'))->render()];
          }
        }

      return view('front.pages.group.index',compact('query','articoli'));
    }

    public function getPublisherAbout($slug)
    {
      $query = User::where('slug',$slug)->first();

      if(empty($query))
        $query = Editori::where('slug',$slug)->first();

      if($query->suspended && (Auth::guest() || Auth::user()->id != $query->direttore)) {
        abort(404);
      }

        $components = collect(explode(',', $query->componenti))->filter(function ($value, $key) {
          return $value != "";
        });
        return view('front.pages.group.about',compact('query','components'));
    }

    public function getPublisherArchive($slug)
    { // http://127,0.0.1:8000/{slug}/archive
      if($slug == Auth::user()->slug){
        $query = Articoli::whereNull('id_gruppo')->where('id_autore', Auth::user()->id)->where('status','0')->get();
        return view('front.pages.profile.archive',compact('query'));
      }else{
        return redirect($slug);
      }
    }

    public function getPublisherSettings($slug,$tab = null,Request $request)
    {
      $query = Editori::where('slug',$slug)->first();

      if(!$query->suspended) {
        if($query->direttore != \Auth::user()->id)
          return redirect($slug);

        if(!$tab)
          return redirect($slug.'/settings/edit');

        return view('front.pages.group.settings',compact('query','tab'));
      } else {
        return redirect($slug);
      }
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
      $date = Carbon::parse($query->published_at)->formatLocalized('%A %d %B %Y');
      $time = Carbon::parse($query->published_at)->format('H:i');

      $score = \DB::table('article_score')->where('article_id', $query->id);
      $hasRate = (Auth::user() && \DB::table('article_score')->where('user_id', Auth::user()->id)->where('article_id', $query->id)->count() > 0);

      return view('front.pages.read',compact('query','date','time','tags','options','like','score','hasRate'));
    }

    public function getArticleEdit($id)
    {
      $query = Articoli::find($id);
      if(Auth::user()->hasMemberOf($query->id_gruppo) || (Auth::user()->id == $query->id_autore)){
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
                  ->leftJoin('editori', function($join){
                    $join->on('articoli.id_gruppo', '=', 'editori.id');
                  })
                  ->addSelect('utenti.slug as user_slug', 'utenti.name as user_name', 'utenti.surname as user_surname', 'editori.name as publisher_name', 'editori.slug as publisher_slug',
                              'articoli.titolo as article_title', 'articoli.id_gruppo as id_editore', 'articoli.slug as article_slug', 'articoli.testo as article_text', 'articoli.copertina as copertina', 'articoli.created_at as created_at')
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
