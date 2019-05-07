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
                                  'articoli.bot_message as bot_message', 'articoli.titolo as article_title', 'articoli.id_gruppo as id_editore', 'articoli.slug as article_slug', 'articoli.copertina as copertina',
                                  'articoli.published_at as published_at', 'articoli.created_at as created_at')
                      ->orderBy('published_at','desc')->get();

    $query3 = Editori::where('name', 'like', '%'. $slug. '%')->get();

    $query = $query->merge($query2)
                  ->merge($query3);

    return view('front.pages.search', compact('slug','query','query2','query3'));
  }

  public function getResultsByTagName($slug)
  {
    $query = Articoli::where('tags', 'like', '%'. $slug .'%')
                      ->leftJoin('utenti', 'articoli.id_autore', '=', 'utenti.id')
                      ->leftJoin('editori', function($join){
                          $join->on('articoli.id_gruppo', '=', 'editori.id');
                        })
                        ->addSelect('utenti.slug as user_slug', 'utenti.name as user_name', 'utenti.surname as user_surname', 'editori.name as publisher_name', 'editori.slug as publisher_slug',
                                    'articoli.bot_message as bot_message', 'articoli.titolo as article_title', 'articoli.id_gruppo as id_editore', 'articoli.slug as article_slug', 'articoli.copertina as copertina',
                                    'articoli.published_at as published_at', 'articoli.created_at as created_at')
                      ->orderBy('published_at','desc')
                      ->paginate(6);

    return view('front.pages.tag_search', compact('slug','query'));
  }
}
