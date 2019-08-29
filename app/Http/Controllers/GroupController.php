<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Auth;

use App\Models\User;
use App\Models\Groups;

// SEO
use SEOMeta;
use OpenGraph;
use Twitter;

class GroupController extends Controller
{

    public function getNewGroup()
    {
          SEOMeta::setTitle(trans('label.title.new_group'). ' - Sywrit', false)
                    ->setCanonical(\Request::url());

          return view('front.pages.group.create');
    }

    public function getGroupIndex($group_id, Request $request)
    {
        $query = Groups::find($group_id)->firstOrFail();

          SEOMeta::setTitle($query->name.' - Sywrit', false);

        $INDEX_LIMIT = 9;
        $current_page = ($request->page) ? $request->page : 1;

        $publisher = array();

        return view('front.pages.group.index', compact('query'));
    }

    public function getGroupAbout($group_id)
    {
        $query = Editori::where('id', $group_id)->first();

          SEOMeta::setTitle($query->name.' - Sywrit', false);

        if($query->suspended && (Auth::guest() || Auth::user()->id != $query->direttore)) {
          abort(404);
        }

          $components = collect(explode(',', $query->componenti))->filter(function ($value, $key) {
            return $value != "";
          });
          return view('front.pages.group.about',compact('query','components'));
    }

    public function getGroupArticle($group_id)
    {
        $query = Groups::find($group_id)->firstOrFail();

        return view('front.components.ajax.group.new_article', compact('query'));
    }

    // POST DATA

    public function deleteGroup($id, Request $request)
    {
      $query = Editori::find($id);
      if($query->direttore == Auth::user()->id){
        if(!$query->suspended){
          // elimino tutti gli articoli scritti dalla redazione
          $articoli = Articoli::where('id_gruppo', $query->id);
          foreach($articoli->get() as $value) {

          }
          $articoli->delete();

          $pub_request = PublisherRequest::where('publisher_id', $query->id);
          foreach($pub_request->get() as $value) {
            Notifications::where('type', '1')->where('content_id', $value->id)->delete();
          }
          $pub_request->delete();

          $components = collect(explode(',', $query->componenti));
          // elimino tutti i membri dal gruppo
          foreach($components as $value){
            $query2 = User::find($value);
            $collection = collect(explode(',', $query2->id_gruppo));
            $query2->id_gruppo = $collection->reject(function($val) use ($query) {
              return $val == $query->id;
            })->implode(',');
            $query2->save();
          }
          $query->delete();
        }
        return redirect('/');
      }
      return redirect($query->slug);
    }

    public function postNewGroup(Request $request)
    {
      $this->validate($request, [
        'name' => 'required|min:3|max:30|unique:editori',
        'description' => 'max:160',
      ], [
        'name.required' => 'Il nome della redazione è obbligatorio',
        'name.min' => 'Il nome è troppo corto',
        'name.max' => 'Il nome è troppo lungo',
        'name.unique' => 'Questo nome è stato già preso',
        'description.max' => 'La descrizione è troppo lunga',
      ]);
      $query = new Groups();
      $query->name = $request->name;
      $query->members = Auth::user()->id;
      if($a = $request->cover){
        $this->validate($request,[
          'cover' => 'image|mimes:jpeg,jpg,png',
        ],[
          'cover.image' => 'Devi inserire un\'immagine',
          'cover.image'  => 'Formato immagine non valido',
        ]);

        $fileName = Str::random(64).'.jpg';

        uploadFile($a, array(
          'name' => $fileName,
          'path' => public_path('sf/g/'),
          'width' => '1100',
          'height' => '350',
          'mimetype' => 'jpg',
          'quality' => '100'
        ));

        $query->cover = asset('sf/g/'. $fileName);
      }

      if($a = $request->avatar){
        $this->validate($request,[
          'avatar' => 'image|mimes:jpeg,jpg,png',
        ],[
          'avatar.image' => 'Devi inserire un\'immagine',
          'avatar.image'  => 'Formato immagine non valido',
        ]);

        $fileName = Str::random(64).'.jpg';

        uploadFile($a, array(
          'name' => $fileName,
          'path' => public_path('sf/g/'),
          'width' => '160',
          'height' => '160',
          'mimetype' => 'jpg',
          'quality' => '100'
        ));

        $query->avatar = asset('sf/g/'. $fileName);
      }
      $query->description = $request->description;
      $query->save();
      $query->slug = Str::slug($query->name, '-');
      $query->save();

      $user = User::find(Auth::user()->id);

      if(!empty($user->id_gruppo)) {
        $user->id_gruppo = $user->id_gruppo.','.$query->id;
      } else {
        $user->id_gruppo = $query->id;
      }

      $user->save();
      return redirect('groups/'. $query->slug);
    }

    public function postGroupSettings($slug,Request $request)
    {
      $query = Editori::where('slug',$slug)->first();
      if(!$query->suspended){
        $query->name = $request->name;
        if($a = $request->description){
          $this->validate($request,[
            'description' => 'max:160',
          ],[
            'description.max' => 'La descrizione è troppo lunga',
          ]);
          $query->biography = $request->description;
        }
        if($a = $request->cover){
          $this->validate($request,[
            'cover' => 'image|mimes:jpeg,jpg,png',
          ],[
            'cover.image' => 'Devi inserire un\'immagine',
            'cover.mimes'  => 'Formato immagine non valido',
          ]);
          if(Storage::disk('groups')->exists($query->cover)){
            Storage::disk('groups')->delete($query->cover);
          }
          $resize = '__160x160'.Str::random(64).'.jpg';
          $image = Image::make($a)->resize(1110, 350)->encode('jpg', 100);
          Storage::disk('groups')->put($resize, $image);
          $query->cover = Storage::disk('groups')->url('groups/'.$resize);
        }
        if($a = $request->avatar){
          $this->validate($request,[
            'avatar' => 'image|mimes:jpeg,jpg,png',
          ],[
            'cover.image' => 'Devi inserire un\'immagine',
            'avatar.mimes'  => 'Formato immagine non valido',
          ]);
          if(Storage::disk('groups')->exists($query->avatar)){
            Storage::disk('groups')->delete($query->avatar);
          }
          $fileName = rand().'.jpg';
          $image = Image::make($a)->resize(160, 160)->encode('jpg', 100);
          Storage::disk('groups')->put($fileName, $image);
          $query->avatar = Storage::disk('groups')->url('groups/'.$fileName);
        }
        $query->save();
      }
      return redirect()->back();
    }

    public function promoteUser(Request $request)
    {
      if($request->ajax()) {
        $query = Editori::find($request->publisher_id);
        if(!$query->suspended && $query->direttore == Auth::user()->id && $request->id != Auth::user()->id) {
          $user = User::find($request->id);

          if($user->hasMemberOf($query->id)) {
            $query->direttore = $user->id;
            $query->save();
          }
        }
      }
    }

    public function firedUser(Request $request)
    {
      if($request->ajax()) {
        $query = Editori::find($request->publisher_id);
        if(!$query->suspended && $query->direttore == Auth::user()->id && $request->id != Auth::user()->id) {
          $user = User::find($request->id);

          if($user->hasMemberOf($request->publisher_id)) {
            $collection = collect(explode(',', $user->id_gruppo))->filter(function($value, $key) use ($request) {
              return $value != "" && $value != $request->publisher_id;
            });

            $query->componenti = collect(explode(',', $query->componenti))->filter(function($value, $key) use ($user) {
              return $value != "" && $value != $user->id;
            })->implode(',');
            $query->save();

            $user->id_gruppo = $collection->implode(',');
            $user->save();
          }
        }
      }
    }

}
