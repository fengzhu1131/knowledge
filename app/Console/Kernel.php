<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
	// Commands\Inspire::class,
		Commands\ModifyEnv::class,
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule) {
		// Run once a minute
		//$schedule -> command('queue:work') -> cron('* * * * * *');
		$schedule -> command('modifysolarterm:env') -> dailyAt('02:00');
		//$schedule->command('queue:work')->everyFiveMinutes();
		// Run once a day
		//$schedule->command('queue:work')->daily();
		// Run Mondays at 8:15am
		//$schedule->command('queue:work')->weeklyOn(1, '8:15');
	}

}
