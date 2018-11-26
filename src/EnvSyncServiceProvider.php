<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Magic42\LaravelEnvSync;

use Illuminate\Support\ServiceProvider;
use Jtant\LaravelEnvSync\Reader\File\EnvFileReader;
use Jtant\LaravelEnvSync\Reader\ReaderInterface;
use Jtant\LaravelEnvSync\Writer\File\EnvFileWriter;
use Jtant\LaravelEnvSync\Writer\WriterInterface;

class EnvSyncServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // bindings
        $this->app->bind(ReaderInterface::class, EnvFileReader::class);
        $this->app->bind(WriterInterface::class, EnvFileWriter::class);

        // artisan command
        $this->commands(Console\SyncCommand::class);
        $this->commands(Console\CheckCommand::class);
        $this->commands(Console\DiffCommand::class);
    }

    public function provides()
    {
        return [
            ReaderInterface::class,
            WriterInterface::class,
        ];
    }


}
