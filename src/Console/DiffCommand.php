<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Jtant\LaravelEnvSync\Console;

use Illuminate\Console\Command;
use Jtant\LaravelEnvSync\Reader\ReaderInterface;
use Jtant\LaravelEnvSync\SyncService;

class DiffCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:diff {--src=} {--dest=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the difference between env files';

    /**
     * @var ReaderInterface
     */
    private $reader;

    /**
     * @var int
     */
    private $returnCode = 0;


    /**
     * Create a new command instance.
     *
     * @param ReaderInterface $reader
     */
    public function __construct(ReaderInterface $reader)
    {
        parent::__construct();
        $this->reader = $reader;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        list($src, $dest) = $this->getSrcAndDest();

        $envValues = $this->reader->read($dest);
        $exampleValues = $this->reader->read($src);

        $keys = array_unique(array_merge(array_keys($envValues), array_keys($exampleValues)));
        sort($keys);

        $header = ["Key", basename($dest), basename($src)];
        $lines = [];
        foreach ($keys as $key) {
            $envVal = isset($envValues[$key]) ? $envValues[$key] : $this->errorText();
            $exampleVal = isset($exampleValues[$key]) ? $exampleValues[$key] : $this->errorText();
            $lines[] = [$key, $envVal, $exampleVal];
        }

        $this->table($header, $lines);

        return $this->returnCode;
    }

    private function errorText()
    {
        $this->returnCode = 1;

        return '<error>NOT FOUND</error>';
    }
}
