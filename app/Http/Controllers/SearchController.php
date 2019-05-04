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
    $query = User::where(\DB::raw("concat(name, ' ', surname)"), 'like', '%'. $slug .'%')->get();

    $query2 = Articoli::where('titolo', 'like', '%'. $slug .'%')
                      ->orWhere('tags', 'like', '%'. $slug .'%')
                      ->leftJoin('utenti', 'articoli.id_autore', '=', 'utenti.id')
                      ->leftJoin('editori', function($join){
                          $join->on('articoli.id_gruppo', '=', 'editori.id');
                        })
                      ->addSelect('utenti.slug as user_slug', 'utenti.name as user_name', 'utenti.surname as user_surname', 'editori.name as publisher_name', 'editori.slug as publisher_slug',
                                  'articoli.titolo as article_title', 'articoli.id_gruppo as id_editore', 'articoli.slug as article_slug', 'articoli.copertina as copertina', 'articoli.created_at as created_at')
                      ->orderBy('published_at','desc')
                      ->get();

    $query3 = Editori::where('name', 'like', '%'. $slug. '%')->get();

    $query = $query->merge($query3);

    return view('front.pages.search', compact('slug','query','query2','query3'));
  }

  public function getResultsByTagName(Request $request, $slug)
  {
    $query = Articoli:://skip(( 12 * ($request->page-1) ))->take(12)
                        where('tags', 'like', '%'. $slug .'%')
                        ->join('utenti', 'articoli.id_autore', '=', 'utenti.id')
                        ->addSelect('utenti.slug as user_slug', 'utenti.name as user_name', 'utenti.surname as user_surname',
                                    'articoli.titolo as article_title', 'articoli.slug as article_slug', 'articoli.copertina as copertina', 'articoli.created_at as created_at')
                        ->orderBy('published_at','desc')
                        ->get();

    return view('front.pages.search', compact('slug','query'));
  }
}
