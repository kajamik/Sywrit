<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivitiesReports extends Model
{
  protected $table = 'reported_articles';

  public function getReportName()
  {
    switch($this->report){
      case 0:
        $msg = "Notizia Falsa";
        break;
      case 1:
        $msg = 'Contenuto di natura sessuale';
        break;
      case 2:
        $msg = 'Contenuti violenti o che incitano all\'odio';
        break;
      case 3:
        $msg = 'Promuove il terrorismo o attività criminali';
        break;
      case 4:
        $msg = 'Violazione del diritto d\'autore';
        break;
      case 5:
        $msg = 'Spam';
        break;
      default:
        $msg = '';
    }

    return $msg;
  }

}
