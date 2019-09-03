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

    /*public function getAdministrators($limit = -1)
    {
        $collection = collect();
        $members = $this->getMembers($limit);

        foreach($members as $value) {
          if($value->) {
            $collection->push($value);
          }
        }

        return $collection;
    }*/

    public function getAdmins($limit = -1)
    {
      $query = \DB::table('group_member')
                    ->join('users', 'group_member.user_id', '=', 'users.id')
                    ->join('group_permission', 'users.id', '=', 'group_permission.user_id')
                    ->where('group_permission.permission_level', 'Administrator')
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

    public function getMembers($limit = -1)
    {
      $query = \DB::table('group_member')
                    ->join('users', 'group_member.user_id', '=', 'users.id')
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

}
