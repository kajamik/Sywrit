<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;

class OpController extends Controller
{
  public function __construct()
  {
    // TODO
  }

  public function home()
  {
    return view('tools/home');
  }

}
