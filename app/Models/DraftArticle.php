<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DraftArticle extends Model
{
    protected $table = 'draft_article';

    protected $fillable = ['topic_id', 'titolo', 'tags', 'testo', 'copertina', 'id_gruppo', 'id_autore', 'license', 'scheduled_at'];

    public function getBackground() {
        if($this->copertina && file_exists('sf/ct/'. $this->copertina)) {
            return asset('sf/ct/'. $this->copertina);
        } else {
          return asset('upload/no-image.jpg');
        }
    }
}
