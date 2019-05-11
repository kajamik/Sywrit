<?php

namespace App\Http\Controllers\Toolbox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;

// Models
use App\Models\BotMessage;

class BotMessageController extends Controller
{
  public function index()
  {
    $query = BotMessage::get();
    return view('tools.pages.bot.view', compact('query'));
  }

  // visualizza i messaggi
  public function getMessage()
  {
    return view('tools.pages.bot.message');
  }
  // pagina per creare nuovi messaggi
  public function getCreateMessage()
  {
    return view('front.pages.bot.create');
  }

  public function postCreateMessage()
  {
    // Response
  }
  // elimina i messaggi selezionati
  public function postDeleteMessage()
  {
    // Response
  }

}