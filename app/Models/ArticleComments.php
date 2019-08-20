<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleComments extends Model
{
    protected $table = 'article_comments';

    protected $fillable = ['article_id', 'text', 'user_id'];

    public function user()
    {
      return $this->belongsTo('App\Models\User');
    }

    public function getUserInfo()
    {
      return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function article()
    {
      return $this->belongsTo('App\Models\Articoli');
    }
}
