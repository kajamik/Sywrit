<?php

namespace App\Http\Controllers\Toolbox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;

// Models
use App\Models\User;
use App\Models\ActivitiesReports;

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
    $reports_activity = ActivitiesReports::where('user_id', $query->id)->get();
    return view('tools.pages.users.sheet', compact('query','reports_activity'));
  }

}
