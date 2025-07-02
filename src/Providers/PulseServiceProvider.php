<?php

namespace Backstage\Tools\Providers;

use Backstage\Tools\ToolsPlugin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PulseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define('viewPulse', function () {
            return dd(ToolsPlugin::get()->isAccessible());
        });
    }
}
