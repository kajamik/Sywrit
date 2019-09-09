<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Auth;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupPermission;
use App\Models\GroupConversation;
use App\Models\GroupArticle;
use App\Models\GroupArticleCommit;
use App\Models\GroupArticleCorrection;
use App\Models\GroupJoinRequest;

// SEO
use SEOMeta;
use OpenGraph;
use Twitter;

class GroupController extends Controller
{

    public function index($group_id, Request $request)
    {
        $query = Group::where('id', $group_id)->firstOrFail();

        SEOMeta::setTitle($query->name.' - Sywrit', false);

        $INDEX_LIMIT = 9;
        $current_page = ($request->page) ? $request->page : 1;

        $publisher = array();

        return view('front.pages.group.index', compact('query'));
    }

    public function getNewGroup()
    {
          SEOMeta::setTitle(trans('label.title.new_group'). ' - Sywrit', false)
                    ->setCanonical(\Request::url());

          return view('front.pages.group.create');
    }

    public function getAbout($group_id)
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

    public function getNewArticle($group_id)
    {
        $query = Group::find($group_id)->firstOrFail();
        session(['group_id' => $query->id]);

        return view('front.pages.group.article.new', compact('query'));
    }

    public function getArticle($group_id, $article_id)
    {
        $query = GroupArticle::where('id', $article_id)
                              ->where('group_id', $group_id)
                              ->first();

        SEOMeta::setTitle($query->title.' - Sywrit', false);

        $date = Carbon::parse($query->created_at)->translatedFormat('l j F Y');
        $time = Carbon::parse($query->created_at)->format('H:i');

        return view('front.pages.group.article.read', compact('query', 'date', 'time'));
    }

    public function getArticleEdit($group_id, $article_id)
    {
        $query = GroupArticle::where('id', $article_id)
                            ->where('group_id', $group_id)
                            ->first();

        return view('front.pages.group.article.edit', compact('query'));
    }

    public function getArticleCommit($group_id, $article_id, $commit_id)
    {
        $query = GroupArticleCommit::where('id', $commit_id)->first();

        SEOMeta::setTitle('Commit #'. $query->id .' - Sywrit', false);

        return view('front.pages.group.article.commit', compact('query'));
    }

    public function getMembers($group_id)
    {
        $query = Group::find($group_id);

        SEOMeta::setTitle($query->name.' - Lista membri - Sywrit', false);

        return view('front.pages.group.members', compact('query'));
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
      $query = new Group();
      $query->name = $request->name;
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

      if($request->status == "public") {
        $query->public = '1';
      } else {
        $query->public = '0';
      }
      $query->save();

      $user = User::find(Auth::user()->id);

      if(!empty($user->id_gruppo)) {
        $user->id_gruppo = $user->id_gruppo.','.$query->id;
      } else {
        $user->id_gruppo = $query->id;
      }

      $user->save();

      GroupMember::create([
        'user_id' => Auth::user()->id,
        'group_id' => $query->id,
        'permission_level' => 'Administrator'
      ]);

      return redirect('groups/'. $query->id);
    }

    public function postNewArticle(Request $request)
    {
        $group_id = session('group_id');
        $testo = $request->document__text;

        if(preg_match('/<img*/', $testo)) {
          $testo = $this->convertImages($testo, array('name' => Str::random(16).'.'.Str::random(32),'path' => public_path('sf/ct/')));
        }

        $this->validate($request,[
          'document__title' => 'required|max:191',
          'document__text' => 'required'
        ],[
          'document__title.required' => 'Il titolo dell\'articolo è obbligatorio',
          'document__text.required' => 'Non è consentito pubblicare un articolo senza contenuto',
          'document__text.max' => 'Titolo troppo lungo',
        ]);

        $query = new GroupArticle();
        $query->title = $request->document__title;
        $query->text = $testo;

        // Copertina
        if($a = $request->image) {
          $this->validate($request,[
            'image' => 'image|mimes:jpeg,jpg,png',
          ],[
            'image.image' => 'Devi inserire un\'immagine',
            'image.mimes'  => 'Formato immagine non valido',
          ]);

          $fileName = '__492x340'.Str::random(64).'.jpg';

          uploadFile($a, array(
            'name' => $fileName,
            'path' => public_path('sf/ct/'),
            'width' => '492',
            'height' => '340',
            'mimetype' => 'jpg',
            'quality' => '100'
          ));
          $query->cover = asset('sf/ct/'. $fileName);
        }

        $query->author_id = \Auth::user()->id;
        $query->group_id = $group_id;
        $query->save();

        $chat = GroupConversation::create([
          'user_id' => Auth::user()->id,
          'article_id' => $query->id,
          'group_id' => $group_id
        ]);

        return redirect('groups/'. $group_id. '/article/'. $query->id);
    }

    public function postSettings($slug,Request $request)
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

}
