<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Editori extends Model
{
    protected $table = 'Editori';

    public function articoli() {
        return $this->hasMany('App\Models\Articoli','editoria','id');
    }

    public function getBackground() {
      if($this->copertina){
        return 'storage/publishers/background/'.$this->copertina;
      }else{
        return 'upload/no-copertina.png';
      }
    }

    public function getLogo() {
      if($this->logo){
        return 'storage/publishers/background/'.$this->logo;
      }else{
        return 'upload/no-image.png';
      }
    }
}
