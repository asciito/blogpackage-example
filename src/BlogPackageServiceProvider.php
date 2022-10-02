<?php

namespace asciito\BlogPackage;

use asciito\BlogPackage\View\Components\Alert;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use asciito\BlogPackage\Console\InstallBlogPackage;
use asciito\BlogPackage\Console\MakeFooCommand;

class BlogPackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('calculator', function(Application $app) {
            return new Calculator;
        });

        // $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'blogpackage');

    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('blogpackage.php'),
            ], 'config');

            $this->commands([
                InstallBlogPackage::class,
                MakeFooCommand::class,
            ]);


            if (!class_exists('CreatePostsTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_posts_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_posts_table.php')
                ], 'migrations');
            }

            // or
            // Load the migrations when the user run the command: artisan migrate
            // $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }

        // or schedule the command if we are using the application via CLI
        // if ($this->app->runningInConsole()) {
        //     $this->app->booted(function() {
        //         $schedule = $this->app->make(Schedule::class);
        //         $schedule->command('some:command')->everyMinute();
        //     });
        // }

        // Load the routes directly
        // $this->loadRoutesFrom(__DIR__ . '/../src/routes/web.php');

        $this->registerRoutes();

        $this->registerViews();
    }

    protected function registerViews()
    {
        $this->publishes([
            __DIR__ . '/../resources/views/posts' => resource_path('views/vendor/blogpackage'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../src/View/Components' => app_path('View/Components'),
            __DIR__ . '/../resources/views/components' => resource_path('views/components'),
        ], 'view-components');
    }

    protected function registerAssets()
    {
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('blogpackage'),
        ], 'assets');
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function() {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('blogpackage.prefix'),
            'middleware' => config('blogpackage.middleware'),
        ];
    }
}