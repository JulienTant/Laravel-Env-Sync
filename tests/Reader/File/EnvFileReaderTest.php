<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Magic42\LaravelEnvSync\Tests\Reader\File;


use Jtant\LaravelEnvSync\Reader\File\EnvFileReader;
use Jtant\LaravelEnvSync\Reader\File\FileRequired;
use Jtant\LaravelEnvSync\Reader\ReaderInterface;
use org\bovigo\vfs\vfsStream;
use VirtualFileSystem\FileSystem;

class EnvFileReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    private $fs;
    /**
     * @var ReaderInterface
     */
    private $reader;

    protected function setUp()
    {
        $this->reader = new EnvFileReader();
        $this->fs = vfsStream::setup("read_env");
    }

    /** @test */
    public function it_should_return_array_from_file_content()
    {
        // Arrange
        $filePath = $this->fs->url() . '/.env';

        file_put_contents($filePath, <<<TAG
APP_SECRET=FOO
APP_TEST=BAR
# COMMENT

TEST=ZOO
TAG
        );

        // Act
        $result = $this->reader->read($filePath);

        // Assert
        $this->assertEquals([
            'APP_SECRET' => 'FOO',
            'APP_TEST' => 'BAR',
            'TEST' => 'ZOO',
        ], $result);
    }

    /** @test */
    public function it_should_throw_exception_when_no_file_passed()
    {
        $this->setExpectedException(FileRequired::class);

        // Act
        $this->reader->read();
    }

}
