<?php

namespace Biigle\Modules\Kpis;

use Biigle\Services\Modules;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Biigle\Modules\Kpis\Console\Commands\CountUser;
use Biigle\Modules\Kpis\Console\Commands\CountUniqueUser;
use Biigle\Modules\Kpis\Console\Commands\DetermineStorageUsage;

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
            'namespace' => 'Biigle\Modules\Kpis\Http\Controllers',
            'middleware' => ['web','api'],
        ], function ($router) {
            require __DIR__.'/Http/routes.php';
        });

        $router->group([
            'namespace' => 'Biigle\Modules\Kpis\Http\Controllers',
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
               //__DIR__.'/Http/Controllers/Api/',
            ],
        ]);

        $this->publishes([
            __DIR__.'/public/assets' => public_path('vendor/kpis'),
        ], 'public');

        if ($this->app->runningInConsole()) {
            $this->commands([
                CountUniqueUser::class,
                CountUser::class,
                DetermineStorageUsage::class,
            ]);

            $this->app->booted(function () {
                $schedule = app(Schedule::class);

                $schedule->command(CountUniqueUser::class)
                    ->monthlyOn(1, '0:0')
                    ->onOneServer();

                $schedule->command(DetermineStorageUsage::class)
                    ->monthlyOn(1, '0:0')
                    ->onOneServer();

                $schedule->command(CountUser::class)
                    ->daily()
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
