<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Articoli;

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

    public function getArticle($slug)
    {
      $info = explode('-',$slug, 2);
      $query = Articoli::where('id',$info[0])
              ->where('slug',$info[1])
              ->first();
      return view('front.pages.articolo',compact('query'));
    }
}
