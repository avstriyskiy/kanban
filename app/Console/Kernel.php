<?php

namespace App\Console;

use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Task;
use App\Mail\TaskOverdue;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\TaskController;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Get the timezone that should be used by default for scheduled events.
     *
     * @return \DateTimeZone|string|null
     */
    protected function scheduleTimezone()
    {
        return 'Europe/Moscow';
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function(){
            $now = Carbon::now('Europe/Moscow')->toDateTimeString();
            $task = Task::whereRaw("deadline<='$now'")->get();

            foreach($task as $overdue) {
                if (!$overdue->mail){
                    $overdue->mail()->create();
                    $users = User::where('category_id', $overdue->category_id)->get();

                    foreach ($users as $user){
                        Mail::to($user)->send(new TaskOverdue($overdue));
                    }
                }
            }
        })->name('Overdue tasks sender')->everyMinute();
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
