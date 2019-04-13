<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivitiesReports extends Model
{
  protected $table = 'reported_comments';

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
        $msg = 'Promuove il terrorismo o attività criminali';
        break;
      case 3:
        $msg = 'Spam';
        break;
      default:
        $msg = '';
    }

    return $msg;
  }

}
