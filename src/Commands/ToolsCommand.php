<?php

namespace Backstage\Tools\Commands;

use Illuminate\Console\Command;

class ToolsCommand extends Command
{
    public $signature = 'tools';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
