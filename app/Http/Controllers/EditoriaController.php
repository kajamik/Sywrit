<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

// Models
use App\Models\Editori;
use App\Models\Articoli;

class EditoriaController extends Controller
{
    public function index()
    {
      return view('front.pages.editoria.lista');
    }

    public function getResults()
    {
      $query = Input::get('search_query');
      return view('front.pages.static.search');
    }

    public function getStartPublisher()
    {
      return view('front.pages.editoria.scelta');
    }

    public function getOfferSelected()
    {
      $page = \Cookie::get('registration_step') || '1';
      $link_id = \Cookie::get('registration_token') || Input::get('link_id');
      switch($link_id){
        case 1: $file_name = "gruppo"; break;
        case 2: $file_name = "singolo"; break;
        default: abort(404);
      }

      if(!\Cookie::get('registration_step')){
        // id prodotto
        \Cookie::queue('registration_token',$link_id);
        // pagina
        \Cookie::queue('registration_step','1');
        return redirect('start/offer/?link_id='.$link_id.'&page=1');
      }

      if(Input::get('page') != \Cookie::get('registration_step'))
        return redirect('start/offer/?link_id='.$link_id.'&page='.\Cookie::get('registration_step'));

      if(\Cookie::get('registration_step') == 1)
        return view('front.pages.offerta.'.$file_name);
      elseif(\Cookie::get('registration_step') == 2)
        return view('front.pages.offerta.termini');
      elseif(\Cookie::get('registration_step') == 3)
        return view('front.pages.offerta.registrazione');
    }

    public function changeRegistrationState()
    {
      $link_id = \Cookie::get('registration_token');
      $page = \Cookie::get('registration_step');
      $next_step = $page+1;
      \Cookie::queue('registration_step',$next_step);
      return redirect('start/offer/?link_id='.$link_id.'&page='.$next_step);
    }

    public function postCreaEditoria()
    {

    }

    public function getEditoria($slug,$slug2 = 'features')
    {
      $publisher = array();
      $query = Editori::with('articoli')->where('slug',$slug)->first();
      return view('front.pages.editoria.index',compact('query','slug2','publisher'));
    }

}
