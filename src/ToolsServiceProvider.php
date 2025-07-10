<?php

namespace Backstage\Tools;

use Backstage\Tools\Commands\ToolsCommand;
use Backstage\Tools\Testing\TestsTools;
use BladeUI\Icons\Factory;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Contracts\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ToolsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'backstage-tools';

    public static string $viewNamespace = 'backstage/tools';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('backstage/tools');

                $command->startWith(function () use ($command) {
                    $publishables = [
                        'horizon-config',
                        'pulse-migrations',
                        'pulse-config',
                        'telescope-migrations',
                    ];

                    foreach ($publishables as $publishable) {
                        $command->call('vendor:publish', [
                            '--tag' => $publishable,
                            '--force' => true,
                        ]);
                    }
                });
            });

        // $configFileName = $package->shortName();

        // if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
        //     $package->hasConfigFile();
        // }

        // if (file_exists($package->basePath('/../database/migrations'))) {
        //     $package->hasMigrations($this->getMigrations());
        // }

        // if (file_exists($package->basePath('/../resources/lang'))) {
        //     $package->hasTranslations();
        // }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            $factory->add('tools', array_merge(['path' => __DIR__ . '/../resources/svg'], [
                'prefix' => 'tools',
            ]));
        });

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/tools/{$file->getFilename()}"),
                ], 'tools-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsTools);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'backstage/tools';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('tools', __DIR__ . '/../resources/dist/components/tools.js'),
            // Css::make('tools-styles', __DIR__ . '/../resources/dist/tools.css'),
            // Js::make('tools-scripts', __DIR__ . '/../resources/dist/tools.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            ToolsCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [];
    }
}
