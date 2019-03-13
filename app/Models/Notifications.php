<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
  protected $table = 'notifications';

  public function getPublisherName()
  {
    return $this->belongsTo('App\Models\Editori','sender_id','id');
  }

  public function getUserName()
  {
    return $this->belongsTo('App\Models\User','sender_id','id');
  }

}
