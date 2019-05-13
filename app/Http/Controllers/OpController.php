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
    $user_articles = DB::table('articoli')->whereNull('id_gruppo')->count();
    $cron_users = DB::table('utenti')->where('cron', '1')->count();

    $archs = DB::table('saved_articles')->count();
    $articles = DB::table('articoli')->where('bot_message', '!=', '1')->count();

    $publishers = DB::table('editori')->count();
    $publisher_articles = DB::table('articoli')->whereNotNull('id_gruppo')->count();

    $comments = DB::table('article_comments')->count();
    $answers = DB::table('answer_comments')->count();

    $reported_articles = DB::table('reported_articles')->count();

    return view('tools/home', compact('users','cron_users','user_articles','articles','archs','publishers','publisher_articles','comments','answers','reported_articles'));
  }

}
