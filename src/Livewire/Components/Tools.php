<?php

namespace Backstage\Tools\Livewire\Components;

use Livewire\Component;

class Tools extends Component
{
    public function render()
    {
        $tools = [
            [
                'icon' => 'backstage/tools::svg.horizon',
                'route' => 'horizon.index',
            ],
            [
                'icon' => 'backstage/tools::svg.pulse',
                'route' => 'pulse',
            ],
            [
                'icon' => 'backstage/tools::svg.telescope',
                'route' => 'telescope',
            ],
        ];

        return view('backstage/tools::livewire.tools', compact('tools'));
    }
}
