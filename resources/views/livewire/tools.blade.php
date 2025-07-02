<div wire:poll.1s>
    <div class ="flex items-center gap-3">
        @foreach ($tools as $tool)
            @if ($tool['visible'])
                <a href="{{ route($tool['route']) }}"
                    class="flex items-center gap-2 text-sm text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                    target="_blank" rel="noopener noreferrer">
                    @include($tool['icon'])
                </a>
            @endif
        @endforeach
    </div>

</div>
