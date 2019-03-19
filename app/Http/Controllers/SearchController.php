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

  public function getResults($slug)
  {
    $query = User::where('nome', 'like', $slug .'%')
          ->orWhere('cognome', 'like', $slug .'%')
          ->limit(5)
          ->get();

    $query2 = Articoli::where('titolo', 'like', $slug .'%')
            ->limit(5)
            ->get();

    $query3 = Articoli::where('tags', 'like', $slug. '%')
              ->limit(5)
              ->get();

    $query4 = Editori::where('nome', 'like', $slug. '%')
            ->limit(5)
            ->get();

      $query = $query->merge($query2)
                      ->merge($query3)
                      ->merge($query4);

    return view('front.pages.search', compact('slug','query'));
  }

  public function getResultsByTagName($slug)
  {
    $query = Articoli::where('tags', 'like', '%'. $slug. '%')->get();
    return view('front.pages.search', compact('slug','input','query'));
  }
}
