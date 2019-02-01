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
        $collection = collect(explode(',',$query->followers));
        $count = $query->followers_count;
        if($collection->some(\Auth::user()->id)){
          $collection->splice($collection->search(\Auth::user()->id),1);
          $query->followers_count -= 1;
          $count--;
        }else{
          $collection->push(\Auth::user()->id);
          $query->followers_count += 1;
          $count++;
        }
        $query->followers = $collection->implode(',');
        $query->save();
        return Response::json(['result' => $collection->some(\Auth::user()->id), 'counter' => $count]);
      }catch(ErrorException $error){
        //
      }
  }

}
