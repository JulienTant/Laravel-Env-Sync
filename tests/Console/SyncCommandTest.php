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

class SyncCommandTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [EnvSyncServiceProvider::class];
    }

    /** @test */
    public function it_should_fill_the_env_file_from_env_example()
    {
        // Arrange
        $root = vfsStream::setup();
        $example = "FOO=BAR\nBAR=BAZ\nBAZ=FOO";
        $env = "FOO=BAR\nBAZ=FOO";

        file_put_contents($root->url() . '/.env.example', $example);
        file_put_contents($root->url() . '/.env', $env);

        $this->app->setBasePath($root->url());

        // Act
        Artisan::call('env:sync', [
            '--no-interaction' => true,
        ]);

        // Assert
        $expected = "FOO=BAR\nBAZ=FOO\nBAR=BAZ";
        $this->assertEquals($expected, file_get_contents($root->url() . '/.env'));
    }

    /** @test */
    public function it_should_work_in_reverse_mode()
    {
        // Arrange
        $root = vfsStream::setup();
        $env= "FOO=BAR\nBAR=BAZ\nBAZ=FOO";
        $example  = "FOO=BAR\nBAZ=FOO";

        file_put_contents($root->url() . '/.env.example', $example);
        file_put_contents($root->url() . '/.env', $env);

        $this->app->setBasePath($root->url());

        // Act
        Artisan::call('env:sync', [
            '--no-interaction' => true,
            '--reverse' => true,
        ]);

        // Assert
        $expected = "FOO=BAR\nBAZ=FOO\nBAR=BAZ";
        $this->assertEquals($expected, file_get_contents($root->url() . '/.env.example'));
    }


    /** @test */
    public function it_should_work_when_providing_src_and_dest()
    {
        // Arrange
        $root = vfsStream::setup();
        $example = "FOO=BAR\nBAR=BAZ\nBAZ=FOO";
        $env = "FOO=BAR\nBAZ=FOO";

        file_put_contents($root->url() . '/.foo', $example);
        file_put_contents($root->url() . '/.bar', $env);

        $this->app->setBasePath($root->url());

        // Act
        Artisan::call('env:sync', [
            '--no-interaction' => true,
            '--src' => $root->url() .'/.foo',
            '--dest' => $root->url() .'/.bar'
        ]);

        // Assert
        $expected = "FOO=BAR\nBAZ=FOO\nBAR=BAZ";
        $this->assertEquals($expected, file_get_contents($root->url() . '/.bar'));
    }
}
