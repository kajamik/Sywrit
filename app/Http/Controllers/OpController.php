<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


use Analytics;
use Spatie\Analytics\Period;

use Response;

class OpController extends Controller
{
  public function __construct()
  {
    // TODO
  }

  public function home()
  {
    $analyticsData = Analytics::fetchVisitorsAndPageViews(Period::days(7));

    return view('tools/home');
  }

}
