<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  protected $table = 'notifications';

  /*public function getUserName()
  {
    return $this->belongsTo('App\Models\User','sender_id','id');
  }*/

}
