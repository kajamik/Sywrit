<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledArticles extends Model
{
    protected $table = 'scheduled_articles';

    public function getBackground() {
      $file = $this->storage.'/'.$this->cover;

        if($this->cover && file_exists($file)){
            return $file;
        } else {
          return asset('upload/no-image.jpg');
        }
    }
}
