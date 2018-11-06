<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Jtant\LaravelEnvSync;

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
        $this->publishes([
            __DIR__ . '/../config/env-sync.php' => config_path('env-sync.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/env-sync.php', 'env-sync');

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
