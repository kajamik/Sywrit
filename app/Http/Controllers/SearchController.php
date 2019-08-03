<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;

// Models
use App\Models\Editori;
use App\Models\Articoli;
use App\Models\User;

use SEOMeta;

class SearchController extends Controller
{

  public function getResults(Request $request, $slug)
  {
    // SEO ///////////////////////////////////////////////////

      SEOMeta::setTitle($slug.' - Sywrit', false)
                ->setDescription(config('app.name').': la nuova piattaforma multi-genere di scrittura online.')
                ->setCanonical(\Request::url());

    //-------------------------------------------------------//

    $query = User::where(\DB::raw("concat(name, ' ', surname)"), 'like', '%'. $slug .'%')->get();

    $query2 = Articoli::where('titolo', 'like', '%'. $slug .'%')
                      ->orWhere('tags', 'like', '%'. $slug .'%')
                      ->leftJoin('utenti', 'articoli.id_autore', '=', 'utenti.id')
                      ->leftJoin('editori', function($join){
                          $join->on('articoli.id_gruppo', '=', 'editori.id');
                        })
                      ->leftJoin('article_category', function($join){
                          $join->on('articoli.topic_id', '=', 'article_category.id');
                        })
                      ->addSelect('utenti.slug as user_slug', 'utenti.name as user_name', 'utenti.surname as user_surname', 'editori.name as publisher_name', 'editori.slug as publisher_slug',
                                  'articoli.bot_message as bot_message', 'articoli.titolo as article_title', 'articoli.id_gruppo as id_editore', 'articoli.slug as article_slug', 'articoli.testo as article_text', 'articoli.copertina as copertina',
                                  'articoli.created_at as created_at', 'article_category.id as topic_id', 'article_category.name as topic_name', 'article_category.slug as topic_slug')
                      ->orderBy('created_at','desc')->get();

    //$query3 = Editori::where('name', 'like', '%'. $slug. '%')->get();

    $query = $query->merge($query2);

    return view('front.pages.search', compact('slug','query','query2'));
  }

  public function getResultsByTagName($slug)
  {
    // SEO ///////////////////////////////////////////////////

      SEOMeta::setTitle($slug.' - Sywrit', false)
                ->setDescription(config('app.name').': la nuova piattaforma multi-genere di scrittura online.')
                ->setCanonical(\Request::url());

    //-------------------------------------------------------//

    $query = Articoli::where('tags', 'like', '%'. $slug .'%')
                      ->leftJoin('utenti', 'articoli.id_autore', '=', 'utenti.id')
                      ->leftJoin('editori', function($join){
                          $join->on('articoli.id_gruppo', '=', 'editori.id');
                        })
                      ->leftJoin('article_category', function($join){
                          $join->on('articoli.topic_id', '=', 'article_category.id');
                        })
                        ->addSelect('utenti.slug as user_slug', 'utenti.name as user_name', 'utenti.surname as user_surname', 'editori.name as publisher_name', 'editori.slug as publisher_slug',
                                    'articoli.bot_message as bot_message', 'articoli.titolo as article_title', 'articoli.id_gruppo as id_editore', 'articoli.slug as article_slug', 'articoli.testo as article_text', 'articoli.copertina as copertina',
                                    'articoli.created_at as created_at', 'article_category.id as topic_id', 'article_category.name as topic_name', 'article_category.slug as topic_slug')
                      ->orderBy('created_at','desc')
                      ->paginate(6);

    return view('front.pages.tag_search', compact('slug','query'));
  }
}
