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
    $query = Articoli::where('titolo','like','%'.$slug.'%')->get();
    $query2 = User::where('nome','like','%'.$slug.'%')
              ->orWhere('cognome','like','%'.$slug.'%')
              ->get();

    return view('front.pages.search', compact('slug','query','query2'));
  }

  public function getResultsByTagName($slug)
  {
    $query = Articoli::where('tags', 'like', '%'. $slug. '%')->get();
    return view('front.pages.search', compact('slug','input','query'));
  }
}
