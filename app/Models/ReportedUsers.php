<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivitiesReports extends Model
{
  protected $table = 'reported_users';

  public function getReportName()
  {
    switch($this->report){
      case 0:
        $msg = 'Profilo falso';
        break;
      case 1:
        $msg = 'Contenuti che violano i nostri standard';
        break;
      case 2:
        $msg = 'Altro';
        break;
      default:
        $msg = '';
    }

    return $msg;
  }

}
