<?php

namespace App\Http\Controllers\Toolbox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;

use Artisan;
use Response;

class OptimizeController extends Controller
{

    private $root;

    public function __construct()
    {
        $this->root = "D:\\Git\\Repository\\Sywrit";
    }

    public function index()
    {
        $root = $this->root;
        return view('tools.pages.optimize', compact('root'));
    }

    public function toEmpty(Request $request)
    {
        $cache = $request->cache;
        $route = $request->route;
        $view = $request->view;
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

        if($output != "") {
          return redirect()->back()->with('output', $output);
        } else {
          return redirect()->back()->with('output', 'Non è successo niente...');
        }
      }

      public function run(Request $request)
      {
          exec('cd '. $this->root . ' && '. $request->cmd, $output);
          return Response::json(['string' => $output]);
      }
}
