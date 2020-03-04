<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Jtant\LaravelEnvSync\Reader\File;

use Dotenv\Dotenv;
use Dotenv\Environment\DotenvFactory;
use Jtant\LaravelEnvSync\Reader\ReaderInterface;

class EnvFileReader implements ReaderInterface
{
    /**
     * Load `.env` file in given directory.
     *
     * @param string $resource
     *
     * @return array
     *
     * @throws FileRequired
     */
    public function read($resource = null)
    {
        if ($resource === null) {
            throw new FileRequired();
        }


        $dir = "";
        $name = "";
        if ($resource != null) {
            $dir = dirname($resource);
            $name = basename($resource);
        }

        return Dotenv::createImmutable($dir, $name)->load();
    }
}
