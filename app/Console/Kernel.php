<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use DB;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\OpDB',
        'App\Console\Commands\Markdown'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      // Account Deletion
        $schedule->call(function () {
          $user = DB::table('utenti')->where('cron', '1')->get();

          foreach($user as $value) {
            $request = DB::table('account_deletion_request')->where('user_id', $value->id);
            if($request->count() && Carbon::parse($request->first()->expired_at) <= Carbon::now()->format('Y-m-d H:i:s')) {
              DB::table('articoli')->whereNull('id_gruppo')->where('id_autore', $value->id)->delete();
              DB::table('notifications')->where('sender_id', $valur->id)->orWhere('target_id', $value->id)->delete();
              DB::table('publisher_request')->where('user_id', $value->id)->orWhere('target_id', $value->id)->delete();
              DB::table('reported_users')->where('reported_id', $value->id)->delete();
              DB::table('utenti')->where('id', $value->id)->delete();
              $request->delete();
            }
          }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
