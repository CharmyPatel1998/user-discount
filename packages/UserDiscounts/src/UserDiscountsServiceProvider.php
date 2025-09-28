<?php

namespace UserDiscounts;

use Illuminate\Support\ServiceProvider;

class UserDiscountsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/config/userdiscounts.php' => config_path('userdiscounts.php')
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/userdiscounts.php', 'userdiscounts'
        );
    }
}
