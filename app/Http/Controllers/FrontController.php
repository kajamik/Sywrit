<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

// Models
use App\Models\User;
use App\Models\Articoli;
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
        $INDEX_LIMIT = 6;
        $ultimi_articoli = Articoli::take(3)
                          ->join('utenti', 'articoli.id_autore', '=', 'utenti.id')
                          ->addSelect('utenti.slug as user_slug', 'utenti.nome as user_name', 'utenti.cognome as user_surname',
                                      'articoli.titolo as article_title', 'articoli.slug as article_slug', 'articoli.copertina as copertina', 'articoli.created_at as created_at')
                          ->where('status','1')
                          ->orderBy('published_at','desc')
                          ->get();

        $articoli = Articoli::skip(3 + ( $INDEX_LIMIT * ($request->page-1) ))->take($INDEX_LIMIT)
                    ->join('utenti', 'articoli.id_autore', '=', 'utenti.id')
                    ->addSelect('utenti.slug as user_slug', 'utenti.nome as user_name', 'utenti.cognome as user_surname',
                                'articoli.titolo as article_title', 'articoli.slug as article_slug', 'articoli.copertina as copertina', 'articoli.created_at as created_at')
                    ->where('status','1')
                    ->orderBy('published_at','desc')
                    ->get();

          if($request->ajax()){
            if(count($articoli)){
              return ['posts' => view('front.components.ajax.loadAll')->with(compact('articoli'))->render()];
            }
          }

      return view('front.pages.welcome',compact('articoli','ultimi_articoli', 'autore'));
    }

    // PROFILE
    public function getProfile($slug,Request $request)
    {
      $follow = '';
      $query = User::where('slug',$slug)->first();
      if(empty($query)){
        return $this->getPublisherIndex($slug, $request);
      }
      if(\Auth::user() && !empty($query)){
          $follow = in_array(\Auth::user()->id,explode(',',$query->followers));
      }
      $query2 = \App\Models\Articoli::where('status','1')->where('id_autore',$query->id);
      $count = $query2->count();
      $articoli = $query2->take(12)->orderBy('created_at','desc')->get();

      if($count){
        if($request->ajax()){
          $articoli = $query2->skip(($request->page-1)*12)->take(12)->get();
            return ['posts' => view('front.components.ajax.loadArticles')->with(compact('articoli'))->render()];
        }
      }

      $this->listGroups($query);

      return view('front.pages.profile.index',compact('query','query2','follow','count','articoli','group'));
    }

    public function getAbout($slug)
    {
      $query = User::where('slug',$slug)->first();

      if(empty($query))
        return $this->getPublisherAbout($slug);

      $query2 = \App\Models\Articoli::where('status','1')->where('id_autore',$query->id);
      $count = $query2->count();

      if(!$query->accesso && (Auth::guest() || Auth::user()->id != $query->direttore))
        abort(404);

      $followers = array();
      if($query->followers != null)
        $followers = explode(',',$query->followers);
        if(\Auth::user() && !empty($query)){
          $follow = in_array(\Auth::user()->id,$followers);
        }

        $this->listGroups(Auth::user());

      return view('front.pages.profile.about',compact('query','query2','follow','followers','count'));
    }

    public function getPrivateArchive($slug)
    { // http://127,0.0.1:8000/{slug}/archive
      if($slug == Auth::user()->slug){
        $query = Articoli::whereNull('id_gruppo')->where('status','0')->get();
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
      $query = Editori::where('slug',$slug)->first();

        if(!$query->accesso && (Auth::guest() || Auth::user()->id != $query->direttore))
          abort(404);

        $publisher = array();
        $followers = array();

        if($request->ajax()){
            $articoli = Articoli::where('id_gruppo',$query->id)->skip(($request->page-1)*12)->take(12)->get();
            return ['posts' => view('front.components.ajax.loadArticles')->with(compact('articoli'))->render()];
        }

      if($query->followers != null)
        $followers = explode(',',$query->followers);

        if(\Auth::user() && !empty($query)){
          $follow = in_array(\Auth::user()->id,$followers);
        }

      return view('front.pages.group.index',compact('query','publisher','followers','follow'));
    }

    public function getPublisherAbout($slug)
    {
      $query = User::where('slug',$slug)->first();
      if(empty($query))
        $query = Editori::where('slug',$slug)->first();

      if(!$query->accesso && (Auth::guest() || Auth::user()->id != $query->direttore))
        abort(404);

      $followers = array();
      if($query->followers != null)
        $followers = explode(',',$query->followers);
        if(\Auth::user() && !empty($query)){
          $follow = in_array(\Auth::user()->id,$followers);
        }
      return view('front.pages.group.about',compact('query','follow','followers'));
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

    public function getWrite()
    {
      $this->listGroups(Auth::user());
      return view('front.pages.new_post');
    }

    public function getArticle($slug)
    {
      $query = Articoli::where('slug',$slug)->orderBy('id', 'asc')->first();

      if( Auth::user() ){
        $options = true;
      }else{
        $options = false;
      }
      $tags = explode(',',$query->tags);
      $collection = collect(explode(',',$query->likes));
      if(Auth::user() && $collection->some(\Auth::user()->id)){
        $like = true;
      }else{
        $like = false;
      }
      $date = Carbon::parse($query->created_at)->formatLocalized('%A %d %B %Y');
      $time = Carbon::parse($query->created_at)->format('H:i');
      return view('front.pages.read',compact('query','date','time','tags','options','like'));
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

    /*************/

    public function getNotifications()
    {
      $query = Notifications::where('target_id',Auth::user()->id)->orderBy('created_at','desc')->get();
      return view('front.pages.profile.notifications', compact('query'));
    }

    public function getSettings()
    {
      return view('front.pages.profile.settings');
    }

    public function getPages($slug)
    {
      return view('front.pages.static.'.$slug);
    }

}
