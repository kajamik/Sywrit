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

}
