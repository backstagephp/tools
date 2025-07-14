<?php

namespace Backstage\Tools;

use Backstage\Tools\Http\Middleware\AuthorizeToolsMiddleware;
use Backstage\Tools\Http\Middleware\MustBeLocalMiddleware;
use Backstage\Tools\Panel\Actions\ToolsAction;
use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;

class ToolsPlugin implements Plugin
{
    use EvaluatesClosures;

    protected Closure | bool $canAccessTools = true;

    public function getId(): string
    {
        return 'tools';
    }

    public function register(Panel $panel): void
    {
        app()->register(\Backstage\Tools\Providers\HorizonServiceProvider::class);
        app()->register(\Backstage\Tools\Providers\PulseServiceProvider::class);
        app()->register(\Backstage\Tools\Providers\TelescopeServiceProvider::class);
        // app()->register(\Backstage\Tools\Providers\LogViewerServiceProvider::class);

        if (! empty($panel->getPath())) {
            config([
                'horizon.path' => 'horizon',
            ]);
        } else {
            config([
                'horizon.path' => 'horizon',
            ]);
        }

        config([
            'horizon.middleware' => [
                'web',
                AuthorizeToolsMiddleware::class,
            ],
        ]);

        config([
            'pulse.path' => 'pulse',
        ]);

        config([
            'pulse.middleware' => [
                'web',
                AuthorizeToolsMiddleware::class,
            ],
        ]);

        config([
            'telescope.path' => 'telescope',
        ]);

        config([
            'telescope.middleware' => [
                'web',
                MustBeLocalMiddleware::class,
                AuthorizeToolsMiddleware::class,
            ],
        ]);

        $panel->userMenuItems([
            ToolsAction::make(),
        ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function canAccessTools(bool | Closure $canAccessTools): static
    {
        $this->canAccessTools = $canAccessTools;

        return $this;
    }

    public function isAccessible(): bool
    {
        return $this->evaluate($this->canAccessTools);
    }
}
