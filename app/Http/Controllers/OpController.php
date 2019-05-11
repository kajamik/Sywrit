<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use DB;
use Response;

use App\Models\User;

#use Analytics;
#use Spatie\Analytics\Period;

class OpController extends Controller
{

  public function home()
  {
    $users = DB::table('utenti')->count();

    $archs = DB::table('saved_articles')->count();
    $articles = DB::table('articoli')->count();

    $publishers = DB::table('editori')->count();

    $comments = DB::table('article_comments')->count();
    $answers = DB::table('answer_comments')->count();

    return view('tools/home', compact('users','articles','archs','publishers','comments','answers'));
  }

}
