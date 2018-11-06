<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Jtant\LaravelEnvSync\Console;

use Illuminate\Support\Facades\Notification;
use Jtant\LaravelEnvSync\SyncService;
use Jtant\LaravelEnvSync\Notifications\SlackChannel;
use Jtant\LaravelEnvSync\Notifications\MissingEnvVariables;

class CheckCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:check {--src=} {--dest=} {--reverse} {--notifySlack}';

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
        list($src, $dest) = $this->getSrcAndDest();

        if ($this->option('reverse')) {
            $switch = $src;
            $src = $dest;
            $dest = $switch;
            unset($switch);
        }

        $diffs = $this->sync->getDiff($src, $dest);

        if (count($diffs) === 0) {
            $this->info(sprintf("Your %s file is already in sync with %s", basename($dest), basename($src)));
            return 0;
        }

        $this->info(sprintf("The following variables are not present in your %s file : ", basename($dest)));
        foreach ($diffs as $key => $diff) {
            $this->info(sprintf("\t- %s = %s", $key, $diff));
        }

        $this->info(sprintf("You can use `php artisan env:sync%s` to synchronise them", $this->option('reverse') ? ' --reverse' : ''));

        if ($this->option('notifySlack')) {
            Notification::send(new SlackChannel, new MissingEnvVariables($diffs));
        }

        return 1;
    }
}
