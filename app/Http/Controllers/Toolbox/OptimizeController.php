<?php

namespace App\Http\Controllers\Toolbox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;

use Auth;
use Artisan;
use Response;

class OptimizeController extends Controller
{

    private $root;

    protected $disable = [
      'php artisan u',
      'php artisan up',
      'php artisan down'
    ];

    public function __construct()
    {
        $this->root = realpath('..');
    }

    public function index()
    {
        if(\Auth::user()->permission < 3) {
          return redirect('toolbox');
        }
        $root = $this->root;
        return view('tools.pages.optimize', compact('root'));
    }

    public function toEmpty(Request $request)
    {
        if($request->ajax()) {
          $cache = $request->cache;
          $route = $request->route;
          $view = $request->view;
          $user = $request->users;
          $article = $request->article;
          $output = "";

          if($cache) {
            Artisan::call('config:cache');
            $output .= "Configuration cache cleared!<br/>Configuration cached successfully!<br/>";
            Artisan::call('cache:clear');
            $output .= "Application cache cleared!<br/>";
          }
          if($route) {
            Artisan::call('route:clear');
            $output .= "Route cache cleared!<br/>";
          }
          if($view) {
            Artisan::call('view:clear');
            $output .= "Compiled views cleared!";
          }
          /*if($user) {
            Artisan::call('user:image');
            $output .= "Immagini utente non utilizzate eliminate!";
          }
          if($article) {
            Article::call('article:image');
            $output .= "Immagini articoli non utilizzate eliminate!";
          }*/

          if($output != "") {
            return redirect()->back()->with('output', $output);
          } else {
            return redirect()->back()->with('output', 'Non è successo niente...');
          }
        }
      }

      public function run(Request $request)
      {
          $not = function(Array $a) use($request) {
              foreach($a as $string) {
                if(trim($request->cmd) === trim($string)) {
                  return false;
                }
              }
              return true;
          };

          if($request->ajax()) {
            if($not($this->disable)) {
              exec("cd $this->root && ". $request->cmd, $output);
            } else {
              $output = 'Comando disabilitato!';
            }
            return Response::json(['string' => $output]);
          }
      }


}
