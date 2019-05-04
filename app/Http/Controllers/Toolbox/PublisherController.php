<?php

namespace App\Http\Controllers\Toolbox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;

// Models
use App\Models\Editori;
use App\Models\ReportedArticles;
use App\Models\ReportedComments;
use App\Models\ReportedAComments;

class PublisherController extends Controller
{
  public function index()
  {
    $query = Editori::get();
    return view('tools.pages.pages.view', compact('query'));
  }

  public function getPageSheet($id)
  {
    $query = Editori::find($id);
    $reports_activity = ReportedArticles::where('user_id', $query->id)->get();
    return view('tools.pages.pages.sheet', compact('query','reports_activity'));
  }

  public function getLockPublisher(Request $request)
  {
    if($request->ajax()){
      $query = Editori::find($request->id);
      if(!$query->suspended){
        $query->suspended = '1';
      } else {
        $query->suspended = '0';
      }
      $query->save();

      return Response::json(['suspended' => $query->suspended]);
    }
  }

}
