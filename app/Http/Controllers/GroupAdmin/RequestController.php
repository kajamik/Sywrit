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
use OpenGraph;
use Twitter;

class RequestController extends Controller
{
    public function getJoinRequests($group_id)
    {
        $query = Group::where('id', $group_id)->first();
        $requests = GroupJoinRequest::join('users', 'group_join_request.user_id', '=', 'users.id')
                                      ->addSelect(
                                        'group_join_request.id as request_id',
                                        'users.id as id', 'users.name as name', 'users.surname as surname', 'users.slug as slug', 'users.avatar as avatar'
                                        )
                                      ->where('group_id', $query->id)->get();

        return view('front.pages.group.admin.join_request', compact('query', 'requests'));
    }
}
