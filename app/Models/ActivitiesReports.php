<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivitiesReports extends Model
{
  protected $table = 'activities_reports';

  public function getReportName()
  {
    switch($this->report){
      case 0:
        $msg = 'Contenuto di natura sessuale';
        break;
      case 1:
        $msg = 'Contenuti violenti o che incitano all\'odio';
        break;
      case 2:
        $msg = 'Promuove il terrorismo o attività criminali';
        break;
      case 3:
        $msg = 'Violazione del diritto d\'autore';
        break;
      default:
        $msg = '';
    }

    return $msg;
  }

}
