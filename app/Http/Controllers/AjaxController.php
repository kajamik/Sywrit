<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;

// Models
use App\Models\Editori;
use App\Models\Articoli;
use App\Models\User;

class AjaxController extends Controller
{
  public function follow(Request $request)
  {

    if($request->mode == 'g')
      $query = Editori::find($request->id);
    elseif($request->mode == 'i')
      $query = User::find($request->id);

      try{
        if(!empty($query->followers))
          $a = explode(',',$query->followers);
        else
          $a = array();
        if(!in_array(\Auth::user()->id,$a)){
          array_push($a, \Auth::user()->id);
        }else{
          array_splice($a, array_search(\Auth::user()->id,$a));
        }
        if(!empty($query->followers)){
          $query->followers = implode(',',$a);
        }else{
          $query->followers = implode('',$a);
        }
        $query->save();
        return Response::json(['result' => in_array(\Auth::user()->id,$a), 'counter' => count($a)]);
      }catch(ErrorException $error){
        //
      }
  }
}
