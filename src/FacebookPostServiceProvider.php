<?php

namespace Captenmasin\LaravelFacebookPost\Providers;

use Illuminate\Support\ServiceProvider;
use Captenmasin\LaravelFacebookPost\Services\FacebookPostService;

class FacebookPostServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Publish the config file
        $this->publishes([
            __DIR__ . '/../config/facebook-post.php' => config_path('facebook-post.php'),
        ], 'config');
    }

    /**
     * Register services.
     */
    public function register()
    {
        // Merge package config
        $this->mergeConfigFrom(__DIR__ . '/../config/facebook-post.php', 'facebook-post');

        // Bind the FacebookPostService as a singleton
        $this->app->singleton('facebook-post-service', function ($app) {
            return new FacebookPostService(
                config('facebook-post.page_id'),
                config('facebook-post.access_token')
            );
        });
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return ['facebook-post-service'];
    }
}
