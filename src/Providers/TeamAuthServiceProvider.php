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
        // Publish config file
        $this->publishes([
            __DIR__.'/team-auth.php' => config_path('courier.php'),
        ]);

        // Check if the command is called from the console
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
