<?php

namespace App\Traits;

use App\Models\Achievement;
use App\Models\AchievementProgress;

trait Achiever
{

    /*
      $user->achievementUnlock(id, value = 100)
      $user->addProgress(id, value)
      $user->setProgress(id, value)
      $user->achievementStatus(id)
    */

    private $achievement_id;

    private $progress_value = 0;


    public function achievementUnlock($achievement_id, $progress_value = 100)
    {
        $this->achievement_id = $achievement_id;
        $this->progress_value = $progress_value;
        $this->save();
    }

    public function addProgress($achievement_id, $progress_value)
    {
        $query = AchievementProgress::where('user_id', $this->id)->where('achievement_id', $achievement_id)->first();
        $query->achievement_id = $achievement_id;
        $query->progress_value += $progress_value;
        $query->save();
    }

    public function setProgress($achievement_id, $progress_value)
    {
        $this->achievement_id = $achievement_id;
        $this->progress_value = $progress_value;
        $this->save();
    }

    public function achievementStatus($achievement_id)
    {
        $achievement = AchievementProgress::where('user_id', $this->id)->where('achievement_id', $achievement_id);

        if($achievement->count()) {
          return true;
        }

        return false;
    }

    public function Result($achievement_id)
    {
        return AchievementProgress::where('user_id', $this->id)->where('achievement_id', $achievement_id)->first();
    }

    public function save(Array $options = [])
    {
        if($this->achievementStatus($this->achievement_id)) {
            $query = AchievementProgress::where('user_id', $this->id)->where('achievement_id', $this->achievement_id)->first();
            $query->progress_value = $this->progress_value;
            $query->save();
        } else {
            $query = new AchievementProgress();
            $query->user_id = $this->id;
            $query->achievement_id = $this->achievement_id;
            $query->progress_value = $this->progress_value;
            $query->save();
        }

        // Add User Points
        if($query->progress_value == 100) {
            $achievement = Achievement::find($this->achievement_id)->first();
            $user = \App\Models\User::find($this->id);
            $user->points += $achievement->points;
            $user->save();
        }
    }

}
