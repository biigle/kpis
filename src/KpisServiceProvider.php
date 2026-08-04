<?php

namespace Biigle\Modules\Kpis;

use Biigle\Services\Modules;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Biigle\Modules\Kpis\Console\Commands\CountUser;
use Biigle\Modules\Kpis\Console\Commands\CountUniqueUser;
use Biigle\Modules\Kpis\Console\Commands\CountCitations;
use Biigle\Modules\Kpis\Console\Commands\DetermineStorageUsage;
use Biigle\Modules\Kpis\Console\Commands\SubmitToScorpion;

class KpisServiceProvider extends ServiceProvider
{

   /**
   * Bootstrap the application events.
   *
   * @param Modules $modules
   * @param  Router  $router
   * @return  void
   */
    public function boot(Modules $modules, Router $router)
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'kpis');
        $this->loadMigrationsFrom(__DIR__.'/Database/migrations');

        $router->group([
            'namespace' => 'Biigle\Modules\Kpis\Http\Controllers\Views',
            'middleware' => ['web','can:review'],
        ], function ($router) {
            require __DIR__.'/Http/web.php';
        });

        $router->group([
            'namespace' => 'Biigle\Modules\Kpis\Http\Controllers\Api',
        ], function ($router) {
            require __DIR__.'/Http/api.php';
        });

        $modules->register('kpis', [
            'viewMixins' => [
                'adminMenu',
            ],
            'controllerMixins' => [
                //
            ],
            'apidoc' => [
               __DIR__.'/Http/Controllers/Api/',
            ],
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                CountUniqueUser::class,
                CountUser::class,
                DetermineStorageUsage::class,
                CountCitations::class,
                SubmitToScorpion::class,
            ]);

            $this->app->booted(function () {
                $schedule = app(Schedule::class);

                $schedule->command(CountUniqueUser::class)
                    ->monthly()
                    ->onOneServer();

                $schedule->command(DetermineStorageUsage::class)
                    ->monthly()
                    ->onOneServer();

                $schedule->command(CountUser::class)
                    ->monthly()
                    ->onOneServer();

                $schedule->command(CountCitations::class)
                    ->monthly()
                    ->onOneServer();

                // Runs one day after the other commands to make sure the most recent
                // data is submitted.
                $schedule->command(SubmitToScorpion::class)
                    ->monthlyOn(2, '00:00')
                    ->onOneServer();
            });
        }
    }

    /**
    * Register the service provider.
    *
    * @return  void
    */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/kpis.php', 'kpis');
    }
}
