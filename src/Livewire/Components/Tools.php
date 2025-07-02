<?php

namespace Backstage\Tools\Livewire\Components;

use Backstage\Tools\ToolsPlugin;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class Tools extends Component
{
    public function render()
    {
        $tools = [
            [
                'icon' => 'backstage/tools::svg.horizon',
                'route' => 'horizon.index',
                'visible' => ToolsPlugin::get()->isAccessible(),
            ],
            [
                'icon' => 'backstage/tools::svg.pulse',
                'route' => 'pulse',
                'visible' => ToolsPlugin::get()->isAccessible(),
            ],
            [
                'icon' => 'backstage/tools::svg.telescope',
                'route' => 'telescope',
                'visible' => ToolsPlugin::get()->isAccessible() && App::isLocal()
            ],
        ];

        return view('backstage/tools::livewire.tools', compact('tools'));
    }
}
