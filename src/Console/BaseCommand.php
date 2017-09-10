<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Jtant\LaravelEnvSync\Console;


use Illuminate\Console\Command;

class BaseCommand extends Command
{

    /**
     * @return array
     */
    public function getSrcAndDest()
    {
        if ($this->option('src') != null || $this->option('dest') != null) {
            if ($this->option('src') == null || $this->option('dest') == null) {
                $this->error("You must use either both src and dest options, or none.");
                exit(1);
            }
        }

        $src = $this->option('src') ? $this->option('src') : base_path('.env.example');
        $dest = $this->option('dest') ? $this->option('dest') : base_path('.env');

        return array($src, $dest);
    }
}