<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Magic42\LaravelEnvSync\Reader\File;

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
        return (new Loader($resource))->load();
    }
}
