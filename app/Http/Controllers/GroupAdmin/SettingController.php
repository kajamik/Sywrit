<?php

namespace App\Http\Controllers\GroupAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Auth;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupJoinRequest;

// SEO
use SEOMeta;

class SettingController extends Controller
{
    public function index($group_id)
    {
        $query = Group::where('id', $group_id)->first();

        SEOMeta::setTitle(trans('Impostazioni di') . ' '. $query->name, false);

        return view('front.pages.group.admin.home', compact('query'));
    }
}
