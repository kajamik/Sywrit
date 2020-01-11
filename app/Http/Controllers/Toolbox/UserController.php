<?php

namespace App\Http\Controllers\Toolbox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;
use Auth;

// Models
use App\Models\User;
use App\Models\ReportedUsers;
use App\Models\ReportedArticles;
use App\Models\Log;

class UserController extends Controller
{
  public function index()
  {
    $query = User::get();
    return view('tools.pages.users.view', compact('query'));
  }

  public function getUserSheet($id)
  {
    $query = User::find($id);
    $user_reports = ReportedUsers::where('reported_users.reported_id', $query->id)->get();
    $reports_activity = ReportedArticles::where('user_id', $query->id)->get();

    return view('tools.pages.users.sheet', compact('query','user_reports','reports_activity'));
  }

  public function getLockAccount(Request $request)
  {
    if($request->ajax()){
      $query = User::find($request->id);
      if(!$query->suspended){
        $query->suspended = '1';
        Log::create([
          'op_id' => Auth::user()->id,
          'user_id' => $query->id,
          'task' => 'locked'
        ]);
      } else {
        $query->suspended = '0';
        Log::create([
          'op_id' => Auth::user()->id,
          'user_id' => $query->id,
          'task' => 'unlocked'
        ]);
      }
      $query->save();

      return Response::json(['suspended' => $query->suspended]);
    }
  }

}
