<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportedUsers extends Model
{
  protected $table = 'reported_users';

  public function getReportName()
  {
    switch($this->report){
      case 0:
        $msg = 'Furto d\'identità';
        break;
      case 1:
        $msg = 'Privacy';
        break;
      case 2:
        $msg = 'Promuove contenuti inappropriati';
        break;
      case 3:
        $msg = 'Spam o truffa';
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
