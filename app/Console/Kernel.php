<?php

namespace App\Console;

use App\Console\Commands\NewProductCron;
use App\EmailTemplate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\NewProductCron::class
    ];


    protected function scheduleTimezone()
    {
        return 'America/New_York';
    }


    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $template = EmailTemplate::find(14);
        $day = (int) $template->day;
        $time = $template->time;

        $schedule->command('newproduct:cron')->weeklyOn($day, $time);
        $schedule->command('updatewinning:products')->daily();
        $schedule->command('process:webhook')->daily();
        //$schedule->command('check_monthly_sales_target:non_shopify_user')->monthly();
        //$schedule->command('check_monthly_sales_target:shopify_user')->monthly();
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
