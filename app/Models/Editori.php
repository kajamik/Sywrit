<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Editori extends Model
{
    protected $table = 'editori';

    public function articoli() {
        return $this->hasMany('App\Models\Articoli','id_gruppo','id');
    }

    public function getBackground() {

      if($this->cover){
        return $this->cover;
      }else{
        return asset('upload/bg.jpg');
      }
    }

    public function getAvatar() {

      if($this->avatar){
        return $this->avatar;
      }else{
        return asset('upload/default.png');
      }
    }

    public function hasMember() {
      $collection = collect(explode(',',$this->componenti));
      return $collection->some(\Auth::user()->id);
    }

    public function getMembers() {
      return collect($this->componenti);
    }
}
