<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\Achievement;


/*
  use:
    $user->achievement($achievement_name)->unlock();
    $user->achievement($id)->isUnlocked(); return true or false
*/

class AchievementController extends Controller
{

    public function __construct($name)
    {
    }

    public function isUnlocked()
    {

    }

}
