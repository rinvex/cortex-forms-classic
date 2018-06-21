<?php

declare(strict_types=1);

namespace Cortex\Forms\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:install:forms {--force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Cortex Forms Module.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->warn($this->description);

        $this->call('cortex:migrate:forms', ['--force' => $this->option('force')]);
        $this->call('cortex:publish:forms', ['--force' => $this->option('force')]);
        $this->call('cortex:seed:forms');
    }
}
