<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportedAComments extends Model
{
  protected $table = 'reported_answer';

  public function getReportName()
  {
    switch($this->report){
      case 0:
        $msg = 'Contenuto di natura sessuale';
        break;
      case 1:
        $msg = 'Contenuto violento o che incitano all\'odio';
        break;
      case 2:
        $msg = 'Molestie o bullismo';
        break;
      case 3:
        $msg = 'Promuove il terrorismo o attività criminali';
        break;
      case 4:
        $msg = 'Spam';
        break;
      default:
        $msg = '';
    }
    return $msg;
  }

  public function getAutore() {
    return $this->belongsTo('App\Models\User','user_id','id');
  }
  
}
