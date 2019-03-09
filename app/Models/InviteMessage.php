<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InviteMessage extends Model
{
  protected $table = 'invite_message';

  public function getEditorName()
  {
    return $this->belongsTo('App\Models\Editori','sender_id','id');
  }

}
