<?php

namespace Jtant\LaravelEnvSync\Console;

use Jtant\LaravelEnvSync\SyncService;
use Illuminate\Console\Command;

class SyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:sync {--reverse}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronise the .env & .env.example files';
    /**
     * @var SyncService
     */
    private $sync;

    /**
     * Create a new command instance.
     *
     * @param SyncService $sync
     */
    public function __construct(SyncService $sync)
    {
        parent::__construct();
        $this->sync = $sync;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $io = $this->getOutput();

        $source = base_path('.env.example');
        $destination = base_path('.env');

        if ($this->option('reverse')) {
            $switch = $source;
            $source = $destination;
            $destination = $switch;
            unset($switch);
        }

        $this->sync->sync($io, $source, $destination);
    }
}
