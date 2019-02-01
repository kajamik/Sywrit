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
    }

    public function index(Request $request)
    {
        // Ultimi articoli
        //$ultimi_articoli = Articoli::inRandomOrder()->paginate(12);
        // Consigliati (- Più votati - Più letti)
        //$consigliati = Articoli::orderBy('letto','desc')->orderBy('piaciuto','desc')->inRandomOrder()->take(12)->get();
        // Preferiti
        //$preferiti = Articoli::get();
        // Editori
        $INDEX_LIMIT = 3;
        $editori = Editori::take($INDEX_LIMIT)->get();
        $utenti = User::take($INDEX_LIMIT-$editori->count())->get();
        if(!empty($editori)){
          if($request->ajax()){
            $editori = Editori::where('last_article', '!=', 'null')->skip(($request->page-1)*$INDEX_LIMIT)->take($INDEX_LIMIT)->get();
            $utenti = User::whereNull('id_gruppo')->where('last_article', '!=', 'null')->skip(($request->page-1)*3)->take(3)->get();
              return ['posts' => view('front.components.ajax.loadAll')->with(compact('editori','utenti'))->render()];
          }
        }

      return view('front.pages.welcome',compact('editori'));
    }

    public function getProfile($slug,Request $request)
    {
      $follow = '';
      $query = User::where('slug',$slug)->first();
      if(\Auth::user() && !empty($query)){
          $follow = in_array(\Auth::user()->id,explode(',',$query->followers));
      }
      $query2 = \App\Models\Articoli::where('autore',$query->id);
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
        $image = Image::make($a)->resize(492, 340)->encode('jpg');
        Storage::disk('articles')->put($resize, $image);
        $image = Image::make($a)->encode('jpg');
        Storage::disk('articles')->put($normal_image, $image);
        $query->copertina = $resize;
      }
      if(\Auth::user()->id_gruppo > 0 && $request->_au == 2)
        $query->id_gruppo = \Auth::user()->id_gruppo;
      $query->autore = \Auth::user()->id;
      $query->letto = '0';
      $query->piaciuto = '0';
      $query->pubblicato = '1';
      $query->save();
      $query->slug = str_slug($query->id.'-'.$query->titolo,'-');
      $query->save();
      if($a){
        $backup = new BackupArticlesImages();
        $backup->img_title = $normal_image;
        $backup->article_id = $query->id;
        $backup->save();
      }
      return redirect('read/'.$query->slug);
    }

    public function getArticle($slug)
    {
      $followers = array();
      $query = Articoli::where('slug',$slug)->first();
      $query2 = Editori::where('id',$query->id_gruppo)->first();
      if(\Auth::user() && !empty($query2)){
        if($query2->followers != null)
          $followers = explode(',',$query2->followers);
        $follow = in_array(\Auth::user()->id,$followers);
      }
      $date = Carbon::parse($query->created_at)->formatLocalized('%A %d %B %Y');
      $time = Carbon::parse($query->created_at)->format('H:i');
      return view('front.pages.read',compact('query','follow','date','time'));
    }

    public function getSettings()
    {
      return view('front.pages.profile.settings');
    }

}
