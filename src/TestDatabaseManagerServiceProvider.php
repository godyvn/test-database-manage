<?php

namespace TestTools\TestDatabaseManager;

use Illuminate\Support\ServiceProvider;
use TestTools\TestDatabaseManager\Console\ResetTestDatabase;

class TestDatabaseManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/testdb.php' => config_path('testdb.php'),
        ], 'testdb-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ResetTestDatabase::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/testdb.php', 'testdb');
    }
}
