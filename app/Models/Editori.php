<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Editori extends Model
{
    protected $table = 'editori';

    private $storage = 'storage/groups';

    public function articoli() {
        return $this->hasMany('App\Models\Articoli','id_gruppo','id');
    }

    public function getBackground() {
      $file = $this->storage.'/'.$this->cover;

      if($this->cover && file_exists($file)){
        return $file;
      }else{
        return 'upload/bg.jpg';
      }
    }

    public function getAvatar() {
      $file = $this->storage.'/'.$this->avatar;

      if($this->avatar && file_exists($file)){
        return $file;
      }else{
        return 'upload/default.png';
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
