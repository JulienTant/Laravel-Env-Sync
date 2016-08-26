<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Jtant\LaravelEnvSync\Tests\Console;


use Artisan;
use Jtant\LaravelEnvSync\EnvSyncServiceProvider;
use Orchestra\Testbench\TestCase;
use org\bovigo\vfs\vfsStream;

class CheckCommandTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [EnvSyncServiceProvider::class];
    }

    /** @test */
    public function it_should_retun_0_when_keys_are_in_both_files()
    {
        // Arrange
        $root = vfsStream::setup();
        $example = "FOO=BAR\nBAR=BAZ\nBAZ=FOO";
        $env = "BAR=BAZ\nFOO=BAR\nBAZ=FOO";

        file_put_contents($root->url() . '/.env.example', $example);
        file_put_contents($root->url() . '/.env', $env);

        $this->app->setBasePath($root->url());

        // Act
        $returnCode = Artisan::call('env:check');

        // Assert
        $this->assertSame(0, (int)$returnCode);
    }


    /** @test */
    public function it_should_retun_1_when_files_are_different()
    {
        // Arrange
        $root = vfsStream::setup();
        $example = "FOO=BAR\nBAR=BAZ\nBAZ=FOO";
        $env = "FOO=BAR\nBAZ=FOO";

        file_put_contents($root->url() . '/.env.example', $example);
        file_put_contents($root->url() . '/.env', $env);

        $this->app->setBasePath($root->url());

        // Act
        $returnCode = Artisan::call('env:check');

        // Assert
        $this->assertSame(1, (int)$returnCode);
    }


    /** @test */
    public function it_should_work_in_reverse_mode()
    {
        // Arrange
        $root = vfsStream::setup();
        $env = "FOO=BAR\nBAR=BAZ\nBAZ=FOO";
        $example = "FOO=BAR\nBAZ=FOO";

        file_put_contents($root->url() . '/.env.example', $example);
        file_put_contents($root->url() . '/.env', $env);

        $this->app->setBasePath($root->url());

        // Act
        $returnCode = Artisan::call('env:check', ["--reverse" => true]);

        // Assert
        $this->assertSame(1, (int)$returnCode);
    }
}
