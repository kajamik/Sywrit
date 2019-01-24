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

    public function getEditoria($slug,Request $request)
    {
      $query = Editori::where('slug',$slug)->first();

        $publisher = array();
        $followers = array();

      //if($count){
        if($request->ajax()){
          $articoli = Articoli::where('id_gruppo',$query->id)->skip(($request->page-1)*12)->take(12)->get();
          return ['posts' => view('front.components.ajax.loadArticles')->with(compact('articoli'))->render()];
        }
      //}

      if($query->followers != null)
        $followers = explode(',',$query->followers);
      $follow = in_array(\Auth::user()->id,$followers);
      return view('front.pages.group.index',compact('query','tab','publisher','followers','follow'));
    }

    public function getEditoriaSettings($slug,$tab = null,Request $request)
    {
      $query = Editori::where('slug',$slug)->first();

      if(!$tab)
        return redirect('group/'.$slug.'/settings/edit');
        
      return view('front.pages.group.settings',compact('query','tab'));
    }
}
