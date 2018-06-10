<?php

namespace AlexTigaer\TeamAuth\Providers;

use Illuminate\Support\ServiceProvider;

class TeamAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if($this->app->runningInConsole()){
            $this->commands([
                'AlexTigaer\TeamAuth\Commands\CreateAuth',
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
