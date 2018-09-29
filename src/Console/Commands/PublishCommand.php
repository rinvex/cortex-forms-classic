<?php

declare(strict_types=1);

namespace Cortex\Forms\Console\Commands;

use Rinvex\Forms\Console\Commands\PublishCommand as BasePublishCommand;

class PublishCommand extends BasePublishCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:publish:forms {--force : Overwrite any existing files.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Cortex Forms Resources.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        parent::handle();

        $this->call('vendor:publish', ['--tag' => 'cortex-forms-lang', '--force' => $this->option('force')]);
        $this->call('vendor:publish', ['--tag' => 'cortex-forms-views', '--force' => $this->option('force')]);
        $this->call('vendor:publish', ['--tag' => 'cortex-forms-config', '--force' => $this->option('force')]);
        $this->call('vendor:publish', ['--tag' => 'cortex-forms-migrations', '--force' => $this->option('force')]);
    }
}
