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

class SyncCommand extends Command
{
    const YES = 'y';
    const NO = 'n';
    const CHANGE = 'c';

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
     * @var WriterInterface
     */
    private $writer;

    /**
     * Create a new command instance.
     *
     * @param SyncService $sync
     */
    public function __construct(SyncService $sync, WriterInterface $writer)
    {
        parent::__construct();
        $this->sync = $sync;
        $this->writer = $writer;
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

        foreach ($diffs as $key => $diff) {
            $question = sprintf("'%s' is not present into your %s file. It's default value is '%s'. Would you like to add it ? [y=yes/n=no/c=change default value]", $key, basename($second), $diff);
            $action = strtolower(trim($this->ask($question, self::YES)));
            if ($action == self::NO) {
                continue;
            }

            if ($action == self::CHANGE) {
                $diff = $this->ask(sprintf("Please choose a value for '%s' :", $key, $diff));
            }

            $this->writer->append($second, $key, $diff);
        }

        $this->info($second . ' is now synced with ' . $first);
    }
}
