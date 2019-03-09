<?php

namespace App\Http\Controllers\Toolbox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;

// Models
use App\Models\ActivitiesReports;

class ReportsActivityController extends Controller
{
  public function index()
  {
    $query = ActivitiesReports::get();
    return view('tools.pages.reports_activity.view', compact('query'));
  }

}
