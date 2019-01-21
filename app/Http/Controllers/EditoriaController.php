<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

// Models
use App\Models\Editori;
use App\Models\Articoli;
use App\Models\User;

class EditoriaController extends Controller
{
    public function index()
    {
      $query = Editori::inRandomOrder()->get();
      return view('front.pages.group.lista',compact('query'));
    }

    public function getResults()
    {
      $input = Input::get('search_query');
      $query = \DB::table('Editori')->where('nome','like','%'.$input.'%')->get();
      $query2 = \DB::table('Utenti')->where('username','like','%'.$input.'%')->get();

      return view('front.pages.search', compact('input','query','query2'));
    }

    public function getStartPublisher()
    {
      return view('front.pages.group.create');
    }

    public function getOfferSelected(Request $request)
    {
      switch($request->link_id){
        case 1: $file_name = "group"; break;
        case 2: $file_name = "individual"; break;
        default: abort(404);
      }
      return view('front.pages.items.'.$file_name);
    }

    public function postBePublisher(Request $request)
    {
      if(!\Auth::user()->hasPublisher()){
        if($request->type == 'g'){
          $this->validate($request,[
            'nome'  =>  'required|string|min:4|max:24|unique:editori',
          ]);

          $query = new Editori();
          $query->nome = $request->input('nome');
          $query->componenti = \Auth::user()->id;
          $query->direttore = \Auth::user()->id;
          $query->avvisi = '0';
          $query->accesso = '1';
          $query->save();
          $query->slug = str_slug($query->nome,'-');
          $query->save();

          $user = User::find(\Auth::user()->id);
          $user->editore = '1';
          $user->id_gruppo = $query->id;
          $user->save();
          return redirect('group/'.$query->slug);
        }elseif($request->type == 'i'){
          $query = User::find(\Auth::user()->id);
          $query->editore = '1';
          $query->save();
          return redirect('profile/'.\Auth::user()->slug);
        }
      }else{
        return redirect('/');
      }
    }

    public function getEditoria($slug,$slug2 = 'featured')
    {
      $publisher = array();
      if($slug2 = 'customize'){
        $publisher['edit'] = true;
      }
      $followers = array();
      $query = Editori::with('articoli')->where('slug',$slug)->first();
      if($query->followers != null)
        $followers = explode(',',$query->followers);
      $follow = in_array(\Auth::user()->id,$followers);
      return view('front.pages.group.index',compact('query','slug2','publisher','followers','follow'));
    }

}
