<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Jtant\LaravelEnvSync\Reader\File;

use Dotenv\Store\File\Reader;
use Illuminate\Support\Env;
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

        $files = Reader::read([$resource], true);

        return (new \Dotenv\Loader\Loader())->load(Env::getRepository(), reset($files));
    }
}
