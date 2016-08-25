<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Jtant\LaravelEnvSync\Reader\File;

use Dotenv\Loader as OriginalLoader;

class Loader extends OriginalLoader
{
    /**
     * Load `.env` file in given directory.
     *
     * @return array
     */
    public function load()
    {
        $this->ensureFileIsReadable();

        $filePath = $this->filePath;
        $lines = $this->readLinesFromFile($filePath);

        $finalLines = [];
        foreach ($lines as $line) {
            if (!$this->isComment($line) && $this->looksLikeSetter($line)) {
                list($name, $value) = $this->normaliseEnvironmentVariable($line, null);
                $finalLines[$name] = $value;
            }
        }

        return $finalLines;
    }
}
