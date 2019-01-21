<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Articoli;
use App\Models\Editori;

class FrontController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
      if(\Auth::user()){
        // Ultimi articoli
        $ultimi_articoli = Articoli::inRandomOrder()->paginate(12);
        // Consigliati (- Più votati - Più letti)
        $consigliati = Articoli::orderBy('letto','desc')->orderBy('piaciuto','desc')->inRandomOrder()->take(12)->get();
        // Preferiti
        $preferiti = Articoli::get();
        return view('front.pages.welcome',compact('ultimi_articoli','consigliati','preferiti'));
      }
      return view('front.pages.home');
    }

    public function getProfile($slug,Request $request)
    {
      $query = User::where('slug',$slug)->first();
      if(\Auth::user()){
          $follow = in_array(\Auth::user()->id,explode(',',$query->followers));
      }
      $query2 = \App\Models\Articoli::where('autore',$query->id);
      $count = $query2->count();
      $articoli = $query2->take(12)->get();
      $group = $query->getPublisherInfo();

      if($count){
        if($request->ajax()){
          $articoli = $query2->skip(($request->page-1)*12)->take(12)->get();
            return ['posts' => view('front.components.ajax.loadArticles')->with(compact('articoli'))->render()];
        }
      }

      return view('front.pages.profile.index',compact('query','followers','follow','count','articoli','group'));
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
      $query->copertina = '';
      $query->id_gruppo = \Auth::user()->id_gruppo;
      $query->autore = \Auth::user()->id;
      $query->letto = '0';
      $query->piaciuto = '0';
      $query->pubblicato = '1';
      $query->save();
      $query->slug = str_slug($query->id.'-'.$query->titolo,'-');
      $query->save();
      return redirect('read/'.$query->slug);
    }

    public function getArticle($slug)
    {
      $followers = array();
      $query = Articoli::where('slug',$slug)->first();
      $query2 = Editori::where('id',$query->id_gruppo)->first();
      if($query2->followers != null)
        $followers = explode(',',$query2->followers);
      $follow = in_array(\Auth::user()->id,$followers);
      return view('front.pages.read',compact('query','follow'));
    }

    public function getSettings()
    {
      return view('front.pages.profile.settings');
    }

}
