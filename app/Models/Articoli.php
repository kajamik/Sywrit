<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articoli extends Model
{
    protected $table = 'articoli';

    public function getBackground() {
      if($this->bot_message != '1') {
        if($this->copertina){
            return $this->copertina;
        } else {
          return asset('upload/no-image.jpg');
        }
      } else {
        return asset('upload/_bot.jpg');
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
