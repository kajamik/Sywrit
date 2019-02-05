<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Articoli;
use App\Models\Editori;
use App\Models\BackupArticlesImages;

use Carbon\Carbon;

use Storage;
use Image;
use File;

class FrontController extends Controller
{
    public function __construct()
    {
      Carbon::setUTF8(true);
      setLocale(LC_TIME, config('app.locale'));
    }

    public function index(Request $request)
    {
        $INDEX_LIMIT = 3;
        $editori = Editori::take($INDEX_LIMIT)->get();
        $utenti = User::take($INDEX_LIMIT-$editori->count())->get();
        if(!empty($editori)){
          if($request->ajax()){
            $editori = Editori::where('last_article', '!=', 'null')->skip(($request->page-1)*$INDEX_LIMIT)->take($INDEX_LIMIT)->get();
            $utenti = User::where('last_article', '!=', 'null')->skip(($request->page-1)*3)->take(3)->get();
              return ['posts' => view('front.components.ajax.loadAll')->with(compact('editori','utenti'))->render()];
          }
        }

      return view('front.pages.welcome',compact('editori'));
    }

    // PROFILE
    public function getProfile($slug,Request $request)
    {
      $follow = '';
      $query = User::where('slug',$slug)->first();
      if(empty($query))
        return $this->getPublisherIndex($slug, $request);
      if(\Auth::user() && !empty($query)){
          $follow = in_array(\Auth::user()->id,explode(',',$query->followers));
      }
      $query2 = \App\Models\Articoli::where('status','2')->where('autore',$query->id);
      $count = $query2->count();
      $articoli = $query2->take(12)->orderBy('created_at','desc')->get();
      $group = $query->getPublisherInfo();

      if($count){
        if($request->ajax()){
          $articoli = $query2->skip(($request->page-1)*12)->take(12)->get();
            return ['posts' => view('front.components.ajax.loadArticles')->with(compact('articoli'))->render()];
        }
      }

      return view('front.pages.profile.index',compact('query','follow','count','articoli','group'));
    }

    public function getPrivateArchive($slug)
    { // http://127,0.0.1:8000/{slug}/archive
      if($slug != \Auth::user()->slug) abort(404);
      $query = Articoli::whereNull('id_gruppo')->where('status','0')->get();
      return view('front.pages.profile.archive',compact('query'));
    }

    /*****************/

    // GROUP
    public function getPublisherIndex($slug,Request $request)
    {
      $query = Editori::where('slug',$slug)->first();

        $publisher = array();
        $followers = array();

      //if($count){
        if($request->ajax()){
          $articoli = Articoli::where('id_gruppo',$query->id)->skip(($request->page-1)*12)->take(12)->get();
          return ['posts' => view('front.components.ajax.loadArticles')->with(compact('articoli'))->render()];
        }
      //}

      if($query->followers != null)
        $followers = explode(',',$query->followers);
      $follow = in_array(\Auth::user()->id,$followers);
      return view('front.pages.group.index',compact('query','tab','publisher','followers','follow'));
    }

    public function getPublisherAbout($slug)
    {
      $query = Editori::where('slug',$slug)->first();
      $followers = array();
      if($query->followers != null)
        $followers = explode(',',$query->followers);
      $follow = in_array(\Auth::user()->id,$followers);
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

    public function getWrite()
    {
      return view('front.pages.new_post');
    }

    public function postWrite(Request $request)
    {
      $query = new Articoli();
      $query->titolo = $request->input('title');
      $query->tags = '';
      $query->testo = $request->input('document__text');
      if($a = $request->image){
        $resize = '__492x340'.Str::random(64).'.jpg';
        $normal_image = '__'.Str::random(64).'.jpg';
        $image = Image::make($a)->crop($request->width[0],$request->height[0],$request->x[0],$request->y[0])->resize(492, 340)->encode('jpg');
        Storage::disk('articles')->put($resize, $image);
        $image = Image::make($a)->encode('jpg');
        Storage::disk('articles')->put($normal_image, $image);
        $query->copertina = $resize;
      }
      if(\Auth::user()->id_gruppo > 0 && $request->_au == 2)
        $query->id_gruppo = \Auth::user()->id_gruppo;
      $query->autore = \Auth::user()->id;
      if($request->save)
        $query->status = '0';
      elseif($query->type == '1')
        $query->status = '2'; // pubblicato
      elseif($query->type == '2')
        $query->status = '1'; // in sospeso
      $query->save();
      $query->slug = str_slug($query->id.'-'.$query->titolo,'-');
      $query->save();
      if($a){
        $backup = new BackupArticlesImages();
        $backup->img_title = $normal_image;
        $backup->article_id = $query->id;
        $backup->save();
      }
      if($query->status){
        $user = User::find(\Auth::user()->id);
        $user->last_article = $query->created_at;
        $user->save();
      }
      return redirect('read/'.$query->slug);
    }

    public function getArticle($slug)
    {
      $followers = array();
      $query = Articoli::where('slug',$slug)->first();
      $tags = explode(',',$query->tags);
      $query2 = Editori::where('id',$query->id_gruppo)->first();
      if(\Auth::user() && !empty($query2)){
        if($query2->followers != null)
          $followers = explode(',',$query2->followers);
        $follow = in_array(\Auth::user()->id,$followers);
      }
      $date = Carbon::parse($query->created_at)->formatLocalized('%A %d %B %Y');
      $time = Carbon::parse($query->created_at)->format('H:i');
      return view('front.pages.read',compact('query','query2','follow','date','time','tags'));
    }

    public function getSettings()
    {
      return view('front.pages.profile.settings');
    }

}
