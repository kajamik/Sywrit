<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articoli extends Model
{
    protected $table = 'articoli';

    private $storage = 'storage/articles';

    public function getBackground() {
      $file = $this->storage.'/'.$this->copertina;

      if($this->bot_message != '1') {
        if($this->copertina && file_exists($file)){
            return $file;
        } else {
          return 'upload/no-image.jpg';
        }
      } else {
        return 'upload/_bot.jpg';
      }
    }

    public function getRedazione() {
      return $this->belongsTo('App\Models\Editori','id_gruppo','id');
    }

    public function getAutore() {
      return $this->belongsTo('App\Models\User','id_autore','id');
    }

    public function getTopic() {
      return $this->belongsTo('App\Models\ArticleCategory','topic_id','id');
    }
}
