<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
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

    public function getMembers($limit = -1)
    {
      $collection = collect();
      $components = collect(explode(',', $this->members))->filter(function ($value, $key) {
        return $value != "";
      });
      if($limit > 0) {
        $components = $components->slice(0, $limit);
      }
      foreach($components as $value) {
        $query = \DB::table('utenti')->where('id', $value)->select('id', 'name', 'surname', 'slug', 'avatar')->first();
        $collection->push($query);
      }
      return $collection;
    }

}
