<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class EditoriaController extends Controller
{
    public function index()
    {
      return view('front.pages.editoria.index');
    }

    public function getCreaEditoria()
    {
      return view('front.pages.editoria.nuovo');
    }

    public function postCreaEditoria()
    {

    }

    public function getEditoria()
    {
      
    }

    public function getResults()
    {
      $query = Input::get('search_query');
      return view('front.pages.static.search');
    }
}
