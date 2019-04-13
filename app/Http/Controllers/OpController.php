<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Response;

use App\Models\User;
use App\Models\Articoli;

use Analytics;
use Spatie\Analytics\Period;

class OpController extends Controller
{
  public function __construct()
  {
    // TODO
  }

  public function home()
  {
    $a = Analytics::fetchVisitorsAndPageViews(Period::days(7));
    $users = User::count();
    $articles = Articoli::count();
    $published_articles = Articoli::where('status','1')->count();
    return view('tools/home', compact('users','articles','published_articles'));
  }

}
