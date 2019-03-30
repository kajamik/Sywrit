<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;

// Models
use App\Models\Editori;
use App\Models\Articoli;
use App\Models\User;

class SearchController extends Controller
{

  public function getResults(Request $request, $slug)
  {
    $query = User::where('nome', 'like', $slug .'%')
          ->orWhere('cognome', 'like', $slug .'%')
          ->limit(5)
          ->get();

    $query2 = Articoli::skip(( 12 * ($request->page-1) ))->take(12)
                      ->where('titolo', 'like', $slug .'%')
                      ->join('utenti', 'articoli.id_autore', '=', 'utenti.id')
                      ->addSelect('utenti.slug as user_slug', 'utenti.nome as user_name', 'utenti.cognome as user_surname',
                                  'articoli.titolo as article_title', 'articoli.slug as article_slug', 'articoli.copertina as copertina', 'articoli.created_at as created_at')
                      ->orderBy('published_at','desc')
                      ->get();



    $query3 = Articoli::skip(( 12 * ($request->page-1) ))->take(12)
                        ->where('tags', 'like', $slug .'%')
                        ->join('utenti', 'articoli.id_autore', '=', 'utenti.id')
                        ->addSelect('utenti.slug as user_slug', 'utenti.nome as user_name', 'utenti.cognome as user_surname',
                                    'articoli.titolo as article_title', 'articoli.slug as article_slug', 'articoli.copertina as copertina', 'articoli.created_at as created_at')
                        ->orderBy('published_at','desc')
                        ->get();

    $query4 = Editori::where('nome', 'like', $slug. '%')
            ->limit(5)
            ->get();

    $query = $query->merge($query2)
                    ->merge($query3)
                    ->merge($query4);

    return view('front.pages.search', compact('slug','query'));
  }

  public function getResultsByTagName(Request $request, $slug)
  {
    $query = Articoli::skip(( 12 * ($request->page-1) ))->take(12)
                        ->where('tags', 'like', $slug .'%')
                        ->join('utenti', 'articoli.id_autore', '=', 'utenti.id')
                        ->addSelect('utenti.slug as user_slug', 'utenti.nome as user_name', 'utenti.cognome as user_surname',
                                    'articoli.titolo as article_title', 'articoli.slug as article_slug', 'articoli.copertina as copertina', 'articoli.created_at as created_at')
                        ->orderBy('published_at','desc')
                        ->get();;
    return view('front.pages.search', compact('slug','input','query'));
  }
}
