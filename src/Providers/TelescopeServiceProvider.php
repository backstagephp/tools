<?php

namespace Backstage\Tools\Providers;

use Backstage\Tools\ToolsPlugin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;


class TelescopeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        }
    }
    
    public function boot(): void
    {
        Gate::define('viewTelescope', function () {
            return ToolsPlugin::get()->isAccessible();
        });
    }
}
