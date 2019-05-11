<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedArticles extends Model
{
    protected $table = 'saved_articles';

    public function getBackground() {
      $file = $this->storage.'/'.$this->copertina;

        if($this->copertina && file_exists($file)){
            return $file;
        } else {
          return 'upload/no-image.jpg';
        }
    }
}
