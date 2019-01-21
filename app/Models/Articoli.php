<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articoli extends Model
{
    protected $table = 'Articoli';

    public function getBackground() {
      if($this->copertina){
        return 'storage/publishers/articoli/'.$this->copertina;
      }else{
        return 'upload/no-image.png';
      }
    }

    public function editoria() {
      return $this->belongsTo('App\Models\Editori','id_gruppo','id');
    }

    public function autore() {
      return $this->belongsTo('App\Models\User','autore','id');
    }
}
