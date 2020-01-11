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
    $users = DB::table('users')->count();
    $user_articles = DB::table('articoli')->whereNull('id_gruppo')->count();
    $cron_users = DB::table('users')->where('cron', '1')->count();

    $archs = DB::table('draft_article')->whereNull('scheduled_at')->count();
    $articles = DB::table('articoli')->where('bot_message', '!=', '1')->count();

    $publishers = DB::table('editori')->count();
    $publisher_articles = DB::table('articoli')->whereNotNull('id_gruppo')->count();

    $comments = DB::table('article_comments')->count();
    $answers = DB::table('answer_comments')->count();
    $reactions = DB::table('article_likes')->count();

    $reported_articles = DB::table('reported_articles')->count();

    $logs = DB::table('log')->orderBy('created_at','desc')->get();

    return view('tools/home', compact('users','cron_users','user_articles','articles','archs','publishers','publisher_articles','comments','answers','reactions','reported_articles','logs'));
  }

}
