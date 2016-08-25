<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Jtant\LaravelEnvSync\Console;

use Jtant\LaravelEnvSync\SyncService;
use Illuminate\Console\Command;
use Jtant\LaravelEnvSync\Writer\WriterInterface;

class CheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:check {--reverse}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if your envs files are in sync';

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
        $first = base_path('.env.example');
        $second = base_path('.env');

        if ($this->option('reverse')) {
            $switch = $first;
            $first = $second;
            $second = $switch;
            unset($switch);
        }

        $diffs = $this->sync->getDiff($first, $second);

        if (count(diffs) === 0) {
            $this->info(sprintf("Your %s file is already in sync with %s", basename($second), basename($first)));
            return 0;
        }

        $this->info(sprintf("The following variables are not present in your %s file : ", basename($second)));
        foreach ($diffs as $key => $diff) {
            $this->info(sprintf("\t- %s = %s", $key, $diff));
        }

        $this->info(sprintf("You can use `php artisan env:sync%s` to synchronise them", $this->option('reverse') ? ' --reverse' : ''));

        return 1;
    }
}
