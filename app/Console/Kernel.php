<?php

namespace App\Console;

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
        //
    ];

    protected $routeMiddleware = [
        'tenancy' => \App\Http\Middleware\TenancyAware::class,
    ];
    
    protected $middlewarePriority = [
        \App\Http\Middleware\TenancyAware::class, // registrasi middleware
        \App\Http\Middleware\Authenticate::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        
        $schedule->call(function () {
            \Illuminate\Support\Facades\Log::info('test task scheduler by dandisy');
        })->weekly();
        
        // $schedule->job(new \App\Jobs\ProcessJob)->monthly();
        $schedule->job(new \App\Jobs\ProcessJob)->weekly()->when(function () {
            return date('W') % 2;
        });
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
