<?php

namespace App\Http\Controllers\Toolbox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;
use Auth;

// Models
use App\Models\Articoli;
use App\Models\Log;

class ArticleController extends Controller
{

    public function getArticleSheet($id)
    {
        $query = Articoli::where('id', $id)->select('id', 'titolo', 'testo', 'id_autore')->first();
        if($query) {
          return view('tools.pages.articles.sheet', compact('query'));
        } else {
          return view('tools.pages.articles.sheet');
        }
    }

    public function postDelete(Request $request)
    {
        $option = $request->option;
        $reason = $request->reason;

        $query = Articoli::where('id', $request->id)->first();

        Log::create([
          'op_id' => Auth::user()->id,
          'user_id' => $query->id,
          'task' => 'delarticle',
          'text' => $reason
        ]);

        $query->delete();

        return redirect('/');
    }
}
