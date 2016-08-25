<?php
namespace Jtant\LaravelEnvSync;

use Illuminate\Support\ServiceProvider;

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
        $this->commands(Console\SyncCommand::class);
    }
}
