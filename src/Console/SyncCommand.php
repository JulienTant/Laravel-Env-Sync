<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Jtant\LaravelEnvSync\Console;

use Illuminate\Console\Command;
use Jtant\LaravelEnvSync\SyncService;
use Jtant\LaravelEnvSync\Writer\WriterInterface;

class SyncCommand extends BaseCommand
{
    const YES = 'y';
    const NO = 'n';
    const CHANGE = 'c';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:sync {--reverse} {--src=} {--dest=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronise the .env & .env.example files.';

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
        list($src, $dest) = $this->getSrcAndDest();


        if ($this->option('reverse')) {
            list($src, $dest) = [$dest, $src];
        }

        $forceCopy = $this->option('no-interaction');
        if ($forceCopy) {
            $this->warn('--no-interaction flag detected - will copy all new keys');
        }


        $diffs = $this->sync->getDiff($src, $dest);

        foreach ($diffs as $key => $diff) {
            $action = self::YES;
            if (!$forceCopy) {
                $question = sprintf("'%s' is not present into your %s file. Its default value is '%s'. Would you like to add it?", $key, basename($dest), $diff);
                $action = $this->choice($question, [
                    self::YES => 'Copy the default value',
                    self::CHANGE => 'Change the default value',
                    self::NO => 'Skip'
                ], self::YES);
            }

            if ($action == self::NO) {
                continue;
            }

            if ($action == self::CHANGE) {
                $diff = $this->output->ask(sprintf("Please choose a value for '%s'", $key, $diff), null, function ($value) {
                    return $value;
                });
            }

            $this->writer->append($dest, $key, $diff);
        }

        $this->info($dest . ' is now synced with ' . $src . '.');
    }
}
