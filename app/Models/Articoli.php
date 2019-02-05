<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articoli extends Model
{
    protected $table = 'Articoli';

    private $storage = 'storage/articles';

    public function getBackground() {
      $file = $this->storage.'/'.$this->copertina;

      if($this->copertina && file_exists($file)){
        return $file;
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
