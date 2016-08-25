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
    protected $signature = 'env:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
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

        $this->sync->sync($io, base_path('.env.example'), base_path('.env'));
    }
}
