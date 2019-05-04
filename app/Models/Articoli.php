<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articoli extends Model
{
    protected $table = 'articoli';

    private $storage = 'storage/articles';

    public function getBackground() {
      $file = $this->storage.'/'.$this->copertina;

      if($this->copertina && file_exists($file)){
        return $file;
      }else{
        return 'upload/no-image.jpg';
      }
    }

    public function getRedazione() {
      return $this->belongsTo('App\Models\Editori','id_gruppo','id');
    }

    public function getAutore() {
      return $this->belongsTo('App\Models\User','id_autore','id');
    }
}
