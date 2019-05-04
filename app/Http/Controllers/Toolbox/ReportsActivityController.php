<?php

namespace App\Http\Controllers\Toolbox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;

// Models
use App\Models\ReportedUsers;
use App\Models\ReportedArticles;
use App\Models\ReportedComments;
use App\Models\ReportedAComments;
use App\Models\User;
use App\Models\Articoli;
use App\Models\ArticleComments;
use App\Models\AnswerComments;

class ReportsActivityController extends Controller
{
  public function index()
  {
    $query = ReportedUsers::union(\DB::table('reported_articles'))
            ->union(\DB::table('reported_comments'))
            ->union(\DB::table('reported_answer'))
            ->orderBy('resolved', 'asc')
            ->paginate(6);

    return view('tools.pages.reports_activity.view', compact('query'));
  }

  public function getView(Request $request)
  {
    if($request->_token) {
      if($sql = ReportedUsers::where('report_token', $request->_token)->first()) {
        $query = User::find($sql->reported_id);
        return view('tools.pages.reports_activity.sheet', compact('sql','query'));
      } elseif($sql = ReportedArticles::where('report_token', $request->_token)->first()) {
        $query = Articoli::find($sql->article_id);
        return view('tools.pages.reports_activity.sheet', compact('sql','query'));
      } elseif($sql = ReportedComments::where('report_token', $request->_token)->first()) {
        $query = Articoli::find($sql->article_id);
        $query2 = ArticleComments::find($sql->comment_id);
        return view('tools.pages.reports_activity.sheet', compact('sql','query','query2'));
      } elseif($sql = ReportedAComments::where('report_token', $request->_token)->first()) {
        $query = Articoli::find($sql->article_id);
        $query2 = AnswerComments::find($sql->answer_id);
        return view('tools.pages.reports_activity.sheet', compact('sql','query','query2'));
      } else {
        return redirect('toolbox/reports_activity');
      }
    } else {
      return redirect('toolbox/reports_activity');
    }
  }

  public function getLockReport(Request $request)
  {
    if($request->ajax()) {
      $query = ReportedUsers::union(\DB::table('reported_articles'))
              ->union(\DB::table('reported_comments'))
              ->union(\DB::table('reported_answer'))
              ->where('report_token', $request->_token)
              ->first();

      $query->resolved = '1';
      $query->save();
      return Response::json(['message' => 'Hai chiuso questo report']);
    }
  }

}
