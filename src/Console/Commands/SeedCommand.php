<?php

declare(strict_types=1);

namespace Cortex\Forms\Console\Commands;

use Illuminate\Console\Command;
use Cortex\Forms\Database\Seeders\CortexFormsSeeder;

class SeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:seed:forms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Cortex Forms Data.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert($this->description);

        $this->call('db:seed', ['--class' => CortexFormsSeeder::class]);

        $this->line('');
    }
}
