<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Jtant\LaravelEnvSync\Reader\File;

use Dotenv\Loader\Loader as OriginalLoader;
use Dotenv\Repository\RepositoryInterface;

class Loader extends OriginalLoader
{
    /**
     * Load `.env` file in given directory.
     *
     * @param RepositoryInterface $repository
     * @param string              $content
     *
     * @return array
     */
    public function load(RepositoryInterface $repository, $content)
    {
        $this->ensureFileIsReadable();

        $filePath = $this->filePath;
        $lines    = $this->readLinesFromFile($filePath);

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
