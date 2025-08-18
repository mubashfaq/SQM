<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CleanExpiredPasswordResetTokens;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CleanExpiredPasswordResetTokens::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(CleanExpiredPasswordResetTokens::class)->everyMinute();
        $schedule->command('products:export')->daily();
         $schedule->command('sitemap:generate')
             ->daily();

        
        $schedule->call(function () {
            $leastSellingProducts = Product::select(
                'products.id',
                'products.title',
                'products.featured_image',
                'products.permalink',
                \DB::raw('COUNT(order_metas.id) as order_count'),
                \DB::raw('SUM(order_metas.quantity) as total_quantity')
            )
                ->leftJoin('order_metas', 'products.id', '=', 'order_metas.product_id')
                ->groupBy('products.id', 'products.title', 'products.featured_image', 'products.permalink')
                ->orderBy('order_count', 'ASC')
                ->take(7)
                ->get();
        })->daily();
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
