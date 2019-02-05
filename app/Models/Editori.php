<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Editori extends Model
{
    protected $table = 'Editori';

    private $storage = 'storage/groups';

    public function articoli() {
        return $this->hasMany('App\Models\Articoli','id_gruppo','id');
    }

    public function getBackground() {
      $file = $this->storage.'/'.$this->background;

      if($this->background && file_exists($file)){
        return $file;
      }else{
        return 'upload/no-copertina.png';
      }
    }

    public function getLogo() {
      $file = $this->storage.'/'.$this->logo;

      if($this->logo && file_exists($file)){
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
