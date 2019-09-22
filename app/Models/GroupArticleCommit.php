<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupArticleCommit extends Model
{
    protected $table = 'group_article_commit';

    protected $fillable = ['article_id', 'user_id', 'old_text', 'new_text', 'note'];

}
