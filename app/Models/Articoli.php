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

    public function getCommentsCount() {
      return $this->integer_format(\App\Models\ArticleComments::where('article_id', $this->id)->count());
    }

    public function getViewCounts() {
      return $this->integer_format($this->count_view);
    }

    public function integer_format($number) {
      if($number > 999999999) {
        $number /= 10000000;
        $resto = $number % 100;
        $number = ($number - $resto) / 100;
        $number = $number . ',' . $resto . ' B';
      }
      elseif($number > 999999) { // 100000
        $number /= 10000;
        $resto = $number % 100;
        $number = ($number - $resto) / 100;
        $number = $number . ',' . $resto . ' Mln';
      } elseif($number > 999) {
        $number = number_format($number, 0, '', '.');
      }
      return $number;
    }
}
