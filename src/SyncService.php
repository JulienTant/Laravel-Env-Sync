<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Jtant\LaravelEnvSync;

use Jtant\LaravelEnvSync\Reader\ReaderInterface;

class SyncService
{
    /**
     * @var ReaderInterface
     */
    private $reader;

    public function __construct(ReaderInterface $reader)
    {
        $this->reader = $reader;
    }

    public function getDiff($source, $destination)
    {
        $this->ensureFileExists($source, $destination);

        $destinationValues = $this->reader->read($destination);
        $sourceValues = $this->reader->read($source);

        $diffKeys = array_diff(array_keys($sourceValues), array_keys($destinationValues));

        return array_filter($sourceValues, function ($key) use ($diffKeys) {
            return in_array($key, $diffKeys);
        }, ARRAY_FILTER_USE_KEY);
    }

    private function ensureFileExists(...$files)
    {
        foreach ($files as $file) {
            if (!file_exists($file)) {
                throw new FileNotFound(sprintf("%s must exists", $file));
            }
        }
    }
}
