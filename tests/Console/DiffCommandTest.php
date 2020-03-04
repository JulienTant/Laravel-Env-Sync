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
use Symfony\Component\Console\Output\BufferedOutput;

class DiffCommandTest extends TestCase
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
        $returnCode = Artisan::call('env:diff', []);

        // Assert

        $expected = <<<TAG
+-----+-----------+--------------+
| Key | .env      | .env.example |
+-----+-----------+--------------+
| BAR | NOT FOUND | BAZ          |
| BAZ | FOO       | FOO          |
| FOO | BAR       | BAR          |
+-----+-----------+--------------+

TAG;

        $this->assertEquals($expected, Artisan::output());
        $this->assertSame(1, (int)$returnCode);
    }
}
