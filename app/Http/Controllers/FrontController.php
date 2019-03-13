<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Articoli;
use App\Models\Editori;
use App\Models\BackupArticlesImages;
use App\Models\Notifications;

use Carbon\Carbon;

use Storage;
use Image;
use File;
use Auth;

class FrontController extends Controller
{

    public function index(Request $request)
    {
        $INDEX_LIMIT = 6;
        // select('id','titolo','autore','slug','copertina','id_gruppo')
        $ultimi_articoli = Articoli::take(3)->where('status','1')->orderBy('published_at','desc')->get();
        $articoli = Articoli::skip(3)->take($INDEX_LIMIT)->select('id','titolo','autore','slug','copertina','id_gruppo')->where('status','1')->orderBy('published_at','desc')->get();

          if($request->ajax()){
            $articoli = Articoli::skip(3 + ( $INDEX_LIMIT * ($request->page-1) ))->take($INDEX_LIMIT)->select('id','titolo','autore','slug','copertina','id_gruppo')->where('status','1')->orderBy('published_at','desc')->get();
            if(count($articoli)){
              return ['posts' => view('front.components.ajax.loadAll')->with(compact('articoli'))->render()];
            }
          }

      return view('front.pages.welcome',compact('articoli','ultimi_articoli'));
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
      $query2 = \App\Models\Articoli::where('status','1')->where('autore',$query->id);
      $count = $query2->count();
      $articoli = $query2->take(12)->orderBy('created_at','desc')->get();

      if($count){
        if($request->ajax()){
          $articoli = $query2->skip(($request->page-1)*12)->take(12)->get();
            return ['posts' => view('front.components.ajax.loadArticles')->with(compact('articoli'))->render()];
        }
      }

      return view('front.pages.profile.index',compact('query','follow','count','articoli'));
    }

    public function getAbout($slug)
    {
      $query = User::where('slug',$slug)->first();

      if(empty($query))
        return $this->getPublisherAbout($slug);

      if(!$query->accesso && (Auth::guest() || Auth::user()->id != $query->direttore))
        abort(404);

      $followers = array();
      if($query->followers != null)
        $followers = explode(',',$query->followers);
        if(\Auth::user() && !empty($query)){
          $follow = in_array(\Auth::user()->id,$followers);
        }
      return view('front.pages.profile.about',compact('query','follow','followers'));
    }

    public function getPrivateArchive($slug)
    { // http://127,0.0.1:8000/{slug}/archive
      if($slug == Auth::user()->slug){
        $query = Articoli::whereNull('id_gruppo')->where('status','0')->get();
        return view('front.pages.profile.archive',compact('query'));
      }else{
        return redirect($slug);
      }
    }

    /*****************/

    // GROUP
    public function getNewPublisher()
    {
      if(!Auth::user()->haveGroup()){
        return view('front.pages.group.create');
      }else{
        return redirect('/');
      }
    }

    public function getPublisherIndex($slug,Request $request)
    {
      $query = Editori::where('slug',$slug)->first();

        if(!$query->accesso && (Auth::guest() || Auth::user()->id != $query->direttore))
          abort(404);

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
      return view('front.pages.new_post');
    }

    public function postWrite(Request $request)
    {
      $this->validate($request,[
        'document__title' => 'required|min:5|max:30',
        'document__text' => 'required'
      ],[
        'document__title.required' => 'Il titolo dell\'articolo è obbligatorio',
        'document__text.required' => 'Non è consentito pubblicare un articolo senza contenuto',
        'document__text.min' => 'Contenuto troppo breve'
      ]);
      $query = new Articoli();
      $query->titolo = $request->document__title;
      $query->tags = '';
      $query->testo = $request->document__text;
      if($a = $request->image){
        $resize = '__492x340'.Str::random(64).'.jpg';
        $normal_image = '__'.Str::random(64).'.jpg';
        $image = Image::make($a)->crop($request->width[0],$request->height[0],$request->x[0],$request->y[0])->resize(492, 340)->encode('jpg');
        Storage::disk('articles')->put($resize, $image);
        $image = Image::make($a)->encode('jpg');
        Storage::disk('articles')->put($normal_image, $image);
        $query->copertina = $resize;
      }
      if(\Auth::user()->id_gruppo > 0 && $request->_au == 2){
        $query->id_gruppo = Auth::user()->id_gruppo;
      }
      $query->autore = \Auth::user()->id;
      if($request->save){
        $query->status = '0';
      }else{
        $query->status = '1'; // pubblicato
      }
      $query->count_view = '0';
      $query->likes_count = '0';
      $query->save();

      // Slug
        $query->slug = str_slug($query->id.'-'.$query->titolo,'-');
        $query->save();
      //
      // Notifications
      if(!empty(Auth::user()->getPublisherInfo()->followers)) {
        foreach(explode(',',Auth::user()->getPublisherInfo()->followers) as $value) {
          if($value != Auth::user()->id) {
            $notifiche = new \App\Models\Notifications();
            if($query->id_gruppo != null){
              $notifiche->sender_id = Auth::user()->getPublisherInfo()->id;
              $notifiche->type = '3';
            }else{
              $notifiche->sender_id = Auth::user()->id;
              $notifiche->type = '2';
            }
            $notifiche->target_id = $value;
            $notifiche->content_id = $query->id;
            $notifiche->marked = '0';
            $notifiche->save();
            $user = User::find($value);
            $user->notifications_count++;
            $user->save();
          }
        }
      }
      /*if($a){
        $backup = new BackupArticlesImages();
        $backup->img_title = $normal_image;
        $backup->article_id = $query->id;
        $backup->save();
      }*/
      /*if($query->status){
        $user = User::find(\Auth::user()->id);
        $user->last_article = $query->created_at;
        $user->save();
      }*/
      return redirect('read/'.$query->slug);
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
      if((Auth::user()->id_gruppo > 0 && $query->id_gruppo == Auth::user()->id_gruppo) || (Auth::user()->id == $query->autore)){
        return view('front.pages.edit_post',compact('query'));
      }else{
        return redirect('read/'.$query->slug);
      }
    }

    /*************/

    public function getNotifications()
    {
      $query = Notifications::where('target_id',Auth::user()->id)->get();
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
