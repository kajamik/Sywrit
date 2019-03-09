<?php

namespace App\Http\Controllers\Toolbox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;

// Models
use App\Models\Editori;
use App\Models\ActivitiesReports;

class PagesController extends Controller
{
  public function index()
  {
    $query = Editori::get();
    return view('tools.pages.pages.view', compact('query'));
  }

  public function getPageSheet($id)
  {
    $query = Editori::find($id);
    $reports_activity = ActivitiesReports::where('user_id', $query->id)->get();
    return view('tools.pages.pages.sheet', compact('query','reports_activity'));
  }

}
