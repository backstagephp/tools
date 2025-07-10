<?php

namespace Backstage\Tools\Panel\Actions;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Backstage\Tools\ToolsPlugin;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\App;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Blade;
use Filament\Schemas\Components\Section;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\HtmlString;
use Opcodes\LogViewer\Facades\LogViewer;

class ToolsAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->icon(fn(): BackedEnum => Heroicon::OutlinedGlobeAlt);

        $this->modal(fn() => true);

        $this->modalIcon(fn(): BackedEnum => $this->getIcon());

        $this->modalHeading(fn(): string => __('Tools'));

        $this->modalDescription(fn(): string => __('Access various tools for application monitoring, health, and debugging.'));

        $this->modalSubmitAction(fn(Action $action) => $action->visible(false));

        $this->modalCancelAction(fn(Action $action): Action => $action->icon(fn(): BackedEnum => Heroicon::XMark)->label(fn() => __('Close')));

        $this->modalFooterActionsAlignment(Alignment::Center);
    }

    public static function getDefaultName(): ?string
    {
        return 'tools';
    }

    public function getSchema(Schema $schema): ?Schema
    {
        $tools = [
            [
                'color' => Color::hex('#7746ec'),
                'description' => __('Monitor and manage queued jobs and background tasks in your Laravel application.'),
                'icon' => 'tools-horizon',
                'label' => 'Horizon',
                'route' => 'horizon.index',
                'visible' => ToolsPlugin::get()->isAccessible(),
            ],
            [
                'color' => Color::hex('#a855f7'),
                'description' => __('Track real-time performance, resource usage, and system health of your application.'),
                'icon' => 'tools-pulse',
                'label' => 'Pulse',
                'route' => 'pulse',
                'visible' => ToolsPlugin::get()->isAccessible(),
            ],
            [
                'color' => Color::hex('#4040c8'),
                'description' => __('Debug your application with deep insights into requests, jobs, queries, and more.'),
                'icon' => 'tools-telescope',
                'label' => 'Telescope',
                'route' => 'telescope',
                'disabled' => App::environment('production'),
                'visible' => ToolsPlugin::get()->isAccessible() && App::isLocal(),
            ],
        ];

        $toolSections = collect($tools)
            ->filter(fn($tool): bool => $tool['visible'])
            ->map(function ($tool): Section {
                return Section::make()
                    ->heading(fn(): string => $tool['label'])
                    ->icon(fn() => $tool['icon'])
                    ->iconColor(fn(): array => $tool['color'])
                    ->columnSpan(fn(): int => 1)
                    ->columns(1)
                    ->headerActions([
                        Action::make('open')
                            ->hiddenLabel()
                            ->outlined()
                            ->color(fn(): array => $tool['color'])
                            ->icon(fn(): BackedEnum => Heroicon::ArrowTopRightOnSquare)
                            ->url(fn(): string => Route::has($tool['route']) ? route($tool['route']) : url($tool['route']), fn(): bool => true)
                    ])
                    ->schema([
                        TextEntry::make('label')
                            ->hiddenLabel()
                            ->state(fn(): string => $tool['description']),
                    ]);
            });

        return $schema->schema([
            Grid::make($toolSections->count())
                ->schema([
                    ...$toolSections
                ])
                ->columnSpanFull()
        ]);
    }
}
