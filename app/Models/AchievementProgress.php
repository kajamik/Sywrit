<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AchievementProgress extends Model
{

    protected $table = 'achievement_progress';

    public function isUnlock()
    {
      if(!is_null($this->created_at)) {
          return true;
      }
      return false;
    }

    public function setProgress($value)
    {
        $this->progress = $this->$progress + $value;
    }

    public function addProgress($value)
    {
        $this->progress = $this->$value;
    }

}
