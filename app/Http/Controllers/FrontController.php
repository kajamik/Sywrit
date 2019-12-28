<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

// Models
use App\Models\BotMessage;
use App\Models\User;
use App\Models\DraftArticle;
use App\Models\Articoli;
use App\Models\ArticleHistory;
use App\Models\ArticleCategory;
use App\Models\Group;
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

// Achievements
use App\Models\Achievement;
use App\Achievements\FirstArticle;

class FrontController extends Controller
{
    public function index(Request $request)
    {
      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle('Sywrit', false)
                  ->setDescription(trans('label.meta.web_description', ['name' => config('app.name')]))
                  ->setCanonical(\Request::url());

      //-------------------------------------------------------//

        $INDEX_LIMIT = 9;
        $current_page = ($request->page) ? $request->page : 1;

        $articoli = Articoli::
                    leftJoin('users', function($join){
                      $join->on('articoli.id_autore', '=', 'users.id');
                    })
                    ->leftJoin('editori', function($join){
                        $join->on('articoli.id_gruppo', '=', 'editori.id');
                      })
                    ->leftJoin('article_category', function($join){
                        $join->on('articoli.topic_id', '=', 'article_category.id');
                      })
                    ->addSelect('users.slug as user_slug', 'users.name as user_name', 'users.surname as user_surname', 'editori.name as publisher_name', 'editori.slug as publisher_slug',
                                'articoli.titolo as article_title', 'articoli.id_gruppo as id_editore', 'articoli.slug as article_slug', DB::raw('articoli.testo as article_text'), 'articoli.copertina as copertina',
                                'articoli.bot_message as bot_message', 'articoli.created_at as created_at', 'article_category.id as topic_id', 'article_category.name as topic_name', 'article_category.slug as topic_slug')
                    ->orderBy('created_at', 'desc')
                    ->skip($INDEX_LIMIT * ($current_page-1))
                    ->take($INDEX_LIMIT)
                    ->get();

          $popular_articles = Articoli::
                              leftJoin('users', function($join){
                                $join->on('articoli.id_autore', '=', 'users.id');
                              })
                              ->leftJoin('editori', function($join){
                                  $join->on('articoli.id_gruppo', '=', 'editori.id');
                                })
                              ->leftJoin('article_category', function($join){
                                  $join->on('articoli.topic_id', '=', 'article_category.id');
                                })
                              ->addSelect('users.slug as user_slug', 'users.name as user_name', 'users.surname as user_surname', 'editori.name as publisher_name', 'editori.slug as publisher_slug',
                                          'articoli.titolo as article_title', 'articoli.id_gruppo as id_editore', 'articoli.slug as article_slug', DB::raw('articoli.testo as article_text'), 'articoli.copertina as copertina',
                                          'articoli.bot_message as bot_message', 'articoli.created_at as created_at', 'article_category.id as topic_id', 'article_category.name as topic_name', 'article_category.slug as topic_slug')
                              ->orderBy('created_at', 'desc')
                              ->skip($INDEX_LIMIT * ($current_page-1))
                              ->take($INDEX_LIMIT)
                              ->limit(5)
                              ->get();

          if($request->ajax()){
            if(count($articoli)){
              return ['posts' => view('front.components.ajax.loadAll')->with(compact('articoli'))->render()];
            }
          }

      return view('front.pages.welcome',compact('articoli', 'popular_articles'));
    }

    // PROFILE
    public function getProfile($slug, Request $request)
    {
      $query = User::where('slug',$slug)->first();

      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle($query->name.' '.$query->surname.' - Sywrit', false)
                  ->setDescription(trans('label.meta.profile_description', ['name' => $query->name.' '.$query->surname]))
                  ->setCanonical(\Request::url());

        OpenGraph::setTitle($query->name.' '.$query->surname.' - Sywrit', false)
                  ->setDescription(trans('label.meta.profile_description', ['name' => $query->name.' '.$query->surname]))
                  ->setType('profile')
                  ->setUrl(\Request::url())
                  ->addImage(asset($query->getAvatar()));

      //-------------------------------------------------------//

      $INDEX_LIMIT = 9;
      $current_page = ($request->page) ? $request->page : 1;

      $articoli = Articoli::leftJoin('article_category', function($join){
                      $join->on('articoli.topic_id', '=', 'article_category.id');
                    })
                    ->addSelect('articoli.titolo as article_title', 'articoli.slug as article_slug', 'articoli.testo as article_text', 'articoli.copertina as copertina',
                                'articoli.created_at as created_at', 'article_category.id as topic_id', 'article_category.name as topic_name', 'article_category.slug as topic_slug')
                    ->where('id_autore', $query->id)
                    ->orderBy('created_at', 'desc');

      $count = $articoli->count();

      $articoli = $articoli->skip($INDEX_LIMIT * ($current_page-1))->take($INDEX_LIMIT)->get();

        if($request->ajax()){
          if($articoli->count()){
            return ['posts' => view('front.components.profile.loadArticles')->with(compact('articoli'))->render()];
          }
        }

      return view('front.pages.profile.index',compact('query','articoli','count'));
    }

    public function getAbout($slug)
    {
      $query = User::where('slug', $slug)->first();

      /*if(empty($query)) {
        return $this->getPublisherAbout($slug);
      }*/

      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle($query->name.' '.$query->surname.' - Sywrit', false)
                  ->setDescription(trans('label.meta.profile_description', ['name' => $query->name.' '.$query->surname]))
                  ->setCanonical(\Request::url());

        OpenGraph::setTitle($query->name.' '.$query->surname.' - Sywrit', false)
                  ->setDescription(trans('label.meta.profile_description', ['name' => $query->name.' '.$query->surname]))
                  ->setType('profile')
                  ->setUrl(\Request::url())
                  ->addImage(asset($query->getAvatar()));

      //-------------------------------------------------------//

      $query2 = \App\Models\Articoli::where('id_autore',$query->id);
      $count = $query2->count();

      return view('front.pages.profile.about', compact('query','query2','count'));
    }

    public function getPrivateArchive()
    {
      $query = Articoli::whereNull('id_gruppo')->where('id_autore', Auth::user()->id)->simplePaginate(6);
      SEOMeta::setTitle('I miei articoli - Sywrit', false)
                ->setCanonical(\Request::url());
      return view('front.pages.profile.article.home', compact('query'));
    }

    /* Draft Articles */
    public function getDraftArticle()
    {
      $query = DraftArticle::whereNull('scheduled_at')->whereNull('id_gruppo')->where('id_autore', Auth::user()->id)->simplePaginate(6);
      SEOMeta::setTitle('Articoli salvati - Sywrit', false)
                ->setCanonical(\Request::url());
      return view('front.pages.profile.article.draft', compact('query'));
    }
    public function getDraftArticleView($id)
    {
      $query = DraftArticle::whereNull('scheduled_at')->where('id', $id)->first();

      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle($query->titolo.' - Sywrit', false)
                  ->setDescription(str_limit(strip_tags($query->testo), 100))
                  ->setCanonical(\Request::url());

      //-------------------------------------------------------//
      if(Auth::user() && ($query->id_gruppo > 0 && Auth::user()->hasMemberOf($query->id_gruppo) || Auth::user()->id == $query->id_autore) ) {

        $options = false;

        if( Auth::user() ){
          $collection = collect(explode(',', Auth::user()->id_gruppo));

          $options = true;
        }

        $tags = explode(',',$query->tags);

        return view('front.pages.profile.article.draft.read', compact('query','tags','options'));
      } else {
        abort(404);
      }
    }

    public function getDraftArticleEdit($id)
    {
      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle(trans('label.title.edit_article'), false)
                  ->setCanonical(\Request::url());

      //-------------------------------------------------------//

      $query = DraftArticle::whereNull('scheduled_at')->whereNull('id_gruppo')->where('id_autore', Auth::user()->id)->where('id', $id)->first();

      $categories = \DB::table('article_category')->orderBy('name', 'asc')->get();

      return view('front.pages.profile.article.draft.edit_post',compact('query','categories'));
    }
    /*****/

    /* Scheduled Articles */
    public function getScheduleArticle()
    {
      $query = DraftArticle::whereNotNull('scheduled_at')->whereNull('id_gruppo')->where('id_autore', Auth::user()->id)->orderBy('scheduled_at','asc')->simplePaginate(6);
      SEOMeta::setTitle('Articoli programmati - Sywrit', false)
                ->setCanonical(\Request::url());
      return view('front.pages.profile.article.scheduled', compact('query'));
    }

    public function getScheduleArticleView($id)
    {
      $query = DraftArticle::whereNotNull('scheduled_at')->whereNull('id_gruppo')->where('id_autore', Auth::user()->id)->where('id', $id)->first();

      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle($query->titolo.' - Sywrit', false)
                  ->setDescription(str_limit(strip_tags($query->testo), 100))
                  ->setCanonical(\Request::url());

      //-------------------------------------------------------//
      if(Auth::user() && ($query->id_gruppo > 0 && Auth::user()->hasMemberOf($query->id_gruppo) || Auth::user()->id == $query->id_autore) ) {

        $options = false;

        if( Auth::user() ){
          $collection = collect(explode(',', Auth::user()->id_gruppo));

          $options = true;
        }

        $tags = explode(',',$query->tags);

        return view('front.pages.profile.article.schedule.read', compact('query','tags','options'));
      } else {
        abort(404);
      }
    }

    public function getScheduleArticleEdit($id)
    {
      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle(trans('label.title.edit_article'), false)
                  ->setCanonical(\Request::url());

      //-------------------------------------------------------//

      $query = DraftArticle::whereNotNull('scheduled_at')->whereNull('id_gruppo')->where('id_autore', Auth::user()->id)->where('id', $id)->first();

      $categories = \DB::table('article_category')->orderBy('name', 'asc')->get();

      return view('front.pages.profile.article.schedule.edit_post', compact('query','categories'));
    }
    /*******/

    public function getAccountDelete()
    {
      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle(trans('label.title.account_deletion'). ' - Sywrit', false)
                  ->setCanonical(\Request::url());

      //-------------------------------------------------------//

        $articoli = Articoli::whereNull('id_gruppo')->where('id_autore', Auth::user()->id);
        $feedback = \DB::table('article_comments')->where('user_id', Auth::user()->id)
                    ->union(\DB::table('answer_comments')->where('user_id', Auth::user()->id));
        return view('front.pages.profile.delete', compact('articoli','feedback'));
    }

    /*****************/

    // GROUP
    /*public function getNewPublisher()
    {
      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle(trans('label.title.new_group'). ' - Sywrit', false)
                  ->setCanonical(\Request::url());

      //-------------------------------------------------------//

        return view('front.pages.group.create');
    }

    public function getPublisherIndex($slug, Request $request)
    {
      $query = Editori::where('slug',$slug)->firstOrFail();

      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle($query->name.' - Sywrit', false)
                  ->setDescription(trans('label.meta.group_description', ['name' => $query->name]))
                  ->setCanonical(\Request::url());

        OpenGraph::setTitle($query->name.' - Sywrit', false)
                  ->setDescription(trans('label.meta.group_description', ['name' => $query->name]))
                  ->setType('publisher')
                  ->setUrl(\Request::url())
                  ->addImage(asset($query->getAvatar()));

      //-------------------------------------------------------//

      $INDEX_LIMIT = 9;
      $current_page = ($request->page) ? $request->page : 1;

      $publisher = array();

      $articoli = Articoli::join('users', 'articoli.id_autore', '=', 'users.id')
                  ->leftJoin('article_category', function($join){
                      $join->on('articoli.topic_id', '=', 'article_category.id');
                    })
                    ->addSelect('users.slug as user_slug', 'users.name as user_name', 'users.surname as user_surname',
                                'articoli.titolo as article_title', 'articoli.slug as article_slug', 'articoli.testo as article_text', 'articoli.copertina as copertina',
                                'articoli.created_at as created_at', 'article_category.id as topic_id', 'article_category.name as topic_name', 'article_category.slug as topic_slug')
                    ->where('articoli.id_gruppo', $query->id)
                    ->orderBy('created_at', 'desc')
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
      $query = Editori::where('slug',$slug)->first();

      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle($query->name.' - Sywrit', false)
                  ->setDescription(trans('label.meta.group_description', ['name' => $query->name]))
                  ->setCanonical(\Request::url());

        OpenGraph::setTitle($query->name.' - Sywrit', false)
                  ->setDescription(trans('label.meta.group_description', ['name' => $query->name]))
                  ->setType('publisher')
                  ->setUrl(\Request::url())
                  ->addImage(asset($query->getAvatar()));

      //-------------------------------------------------------//

      if($query->suspended && (Auth::guest() || Auth::user()->id != $query->direttore)) {
        abort(404);
      }

        $components = collect(explode(',', $query->componenti))->filter(function ($value, $key) {
          return $value != "";
        });
        return view('front.pages.group.about',compact('query','components'));
    }

    public function getPublisherArchive($slug)
    {
        // Redazione
        $query = Editori::where('slug', $slug)->firstOrFail();

        if(!$query->suspended && Auth::user()->hasMemberOf($query->id)){
          SEOMeta::setTitle('Articoli Salvati - '.$query->name.' - Sywrit', false)
                    ->setCanonical(\Request::url());
          $query2 = SavedArticles::where('id_gruppo', $query->id)->get();
          return view('front.pages.group.archive',compact('query','query2'));
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

          SEOMeta::setTitle(trans('label.title.settings').' - '.$query->name.' - Sywrit', false)
                    ->setCanonical(\Request::url());

        return view('front.pages.group.settings', compact('query','tab'));
      } else {
        return redirect($slug);
      }
    }*/

    /*****************/
    /**** ARTICLE ****/
    /*****************/

    public function getWrite(Request $request)
    {

      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle(trans('label.title.new_article'), false)
                  ->setDescription(trans('label.meta.web_description', ['name' => config('app.name')]))
                  ->setCanonical(\Request::url());

      //-------------------------------------------------------//

      if(!$request->_topic){
        $categories = \DB::table('article_category')->orderBy('name', 'asc')->get();
      } else {
        $categories = \DB::table('article_category')->where('slug', $request->_topic)->first();
      }
      return view('front.pages.new_post', compact('categories'));
    }

    public function getArticle($slug)
    {
      $query = Articoli::where('slug', $slug)->first();

      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle($query->titolo.' - Sywrit', false)
                  ->setDescription(str_limit(strip_tags($query->testo), 100))
                  ->setCanonical(\Request::url());

        $og =  collect([
          'published_time' => Carbon::parse($query->created_at),
        ]);

        if($query->bot_message != '1') {
          $og->put('author', $query->getAutore->name.' '.$query->getAutore->surname);
        }

        if($query->created_at != $query->updated_at) {
          $og->put('modified_at', Carbon::parse($query->updated_at));
        }

        if(!empty($query->topic_id)) {
          $og->put('section', $query->getTopic->name);
        }

        $og->put('tag', $query->tags);

        OpenGraph::setTitle($query->titolo.' - Sywrit', false)
                  ->setDescription(str_limit(strip_tags($query->testo), 100))
                  ->setType('article')
                  ->setUrl(\Request::url())
                  ->addImage(asset($query->getBackground()))
                  ->setArticle($og);

      //-------------------------------------------------------//

      if($query->bot_message != '1') {

        $options = false;

        if( Auth::user() ){
          $collection = collect(explode(',', Auth::user()->id_gruppo));

          $options = true;
        }

        $tags = explode(',',$query->tags);
        $date = Carbon::parse($query->created_at)->translatedFormat('l j F Y');
        $time = Carbon::parse($query->created_at)->format('H:i');

        $likes = \DB::table('article_likes')->where('article_id', $query->id)->count();
        $liked = (Auth::user() && \DB::table('article_likes')->where('user_id', Auth::user()->id)->where('article_id', $query->id)->count() > 0);

        return view('front.pages.read',compact('query','date','time','tags','options','likes','liked'));
      } else {
        $tags = explode(',',$query->tags);
        $date = Carbon::parse($query->created_at)->translatedFormat('l j F Y');
        $time = Carbon::parse($query->created_at)->format('H:i');
        return view('front.pages.read_bot_message', compact('query','date','time','tags'));
      }
    }

    public function getArticleEdit($id)
    {
      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle(trans('label.title.edit_article'), false)
                  ->setCanonical(\Request::url());

      //-------------------------------------------------------//

      $query = Articoli::where('id', $id)->first();

      if(($query->id_gruppo && Auth::user()->hasMemberOf($query->id_gruppo)) || (Auth::user()->id == $query->id_autore)){
        return view('front.pages.edit_post', compact('query'));
      } else {
        return redirect('read/'.$query->slug);
      }
    }

    /*************/

    public function getTopic($slug, Request $request)
    {
      $topic = ArticleCategory::where('slug', $slug)->first();

      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle(trans('label.categories.'.$topic->slug).' - Sywrit', false)
                  ->setDescription(trans('label.meta.topic_name', ['name' => $topic->name]))
                  ->setCanonical(\Request::url());

        OpenGraph::setTitle(trans('label.categories.'.$topic->slug).' - Sywrit', false)
                  ->setDescription(trans('label.meta.topic_name', ['name' => $topic->name]))
                  ->setType('section')
                  ->setUrl(\Request::url())
                  ->addImage(asset('upload/topics/'.$topic->slug.'.jpg'));

      //-------------------------------------------------------//

      $INDEX_LIMIT = 9;
      $current_page = ($request->page) ? $request->page : 1;

      $articoli = Articoli::join('users', 'articoli.id_autore', '=', 'users.id')
                  ->leftJoin('editori', function($join){
                    $join->on('articoli.id_gruppo', '=', 'editori.id');
                  })
                  ->addSelect('users.slug as user_slug', 'users.name as user_name', 'users.surname as user_surname', 'editori.name as publisher_name', 'editori.slug as publisher_slug',
                              'articoli.titolo as article_title', 'articoli.id_gruppo as id_editore', 'articoli.slug as article_slug', 'articoli.testo as article_text', 'articoli.copertina as copertina', 'articoli.created_at as created_at')
                  ->where('topic_id', $topic->id)
                  ->orderBy('created_at', 'desc')
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

      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle(trans('label.title.notifications'). ' - Sywrit', false)
                  ->setDescription(trans('label.meta.web_description', ['name' => config('app.name')]))
                  ->setCanonical(\Request::url());

      //-------------------------------------------------------//

      $query = Notifications::where('target_id',Auth::user()->id)->orderBy('created_at','desc')->paginate(6);
      return view('front.pages.profile.notifications', compact('query'));
    }

    public function getAchievement()
    {
      // SEO ///////////////////////////////////////////////////

        SEOMeta::setTitle(trans('label.title.my_objectives'). ' - Sywrit', false)
                  ->setDescription(trans('label.meta.web_description', ['name' => config('app.name')]))
                  ->setCanonical(\Request::url());

      //-------------------------------------------------------//

      $ach = Achievement::get();
      return view('front.pages.profile.achievement', compact('ach'));
    }

    public function getPages($slug, $slug2 = '')
    {
      // SEO ///////////////////////////////////////////////////

        SEOMeta::setDescription(trans('label.meta.web_description', ['name' => config(app.name)]))
                  ->setCanonical(\Request::url());

      //-------------------------------------------------------//

      if($slug2) {
        $slug2 = '/'.$slug2;
      }
      return view('front.pages.static.'.$slug.$slug2);
    }

}
