<?php

namespace Rahman\Todos;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Rahman\Todos\Providers\EventServiceProvider;

class TodoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes/api.php';
        $this->app->make('Rahman\Todos\Controllers\TodoController');
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();

        //Load migration and this package migration files run after user runs the migrate command
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadFactoriesFrom(__DIR__.'/../database/factories');

        //Defines the views folder path
        $this->loadViewsFrom(__DIR__.'/views', 'todos');
        
        //With the command below views copied from package o user views folder after publishing
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/rahman/todos'),
        ]);

    }

    protected function registerRoutes()
{
    Route::group($this->routeConfiguration(), function () {
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
    });
}

protected function routeConfiguration()
{
    return [
        'prefix' => 'api',
        'middleware' => 'auth',
    ];
}

}
