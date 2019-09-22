<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';

    public function getBackground()
    {
      if($this->cover) {
        return $this->cover;
      }
      return asset('upload/bg.jpg');
    }

    public function getAvatar()
    {
      if($this->avatar) {
        return $this->avatar;
      }
      return asset('upload/default.png');
    }

    public function getAdministrators($limit = -1)
    {
      $query = \DB::table('group_member')
                    ->join('users', 'group_member.user_id', '=', 'users.id')
                    ->where('group_member.permission_level', 'Administrator')
                    ->where('group_member.group_id', $this->id);

      if($limit > 0) {
        $query = $query->limit($limit);
      }
      $collection = collect();
      foreach($query->get() as $value) {
        $collection->push($value);
      }
      return $collection;
    }

    public function getMembers($skip = 0, $limit = -1)
    {
      $query = \DB::table('group_member')
                    ->join('users', 'group_member.user_id', '=', 'users.id')
                    ->where('group_member.group_id', $this->id);
      if($skip > 0) {
        $query = $query->skip($skip);
      }
      if($limit > 0) {
        $query = $query->limit($limit);
      }
      $collection = collect();
      foreach($query->get() as $value) {
        $collection->push($value);
      }
      return $collection;
    }

    public function requests()
    {
      $query = \DB::table('group_join_request')->where('group_id', $this->id);
      return $query->count();
    }

}
