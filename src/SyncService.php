<?php
namespace Jtant\LaravelEnvSync;

use File;
use Symfony\Component\Console\Output\OutputInterface;

class SyncService
{

    public function sync(OutputInterface $io, $source, $destination)
    {
        try {
            self::guard($source, $destination);
        } catch (FileNotFound $exception) {
            $io->writeln($exception->getMessage());
        }

        $destinationValues = self::loadEnvFile($destination);
        $sourceValues = self::loadEnvFile($source);

        $destinationContent = [];
        foreach ($sourceValues as $key => $value) {
            if (array_key_exists($key, $destinationValues)) {
                $destinationContent[] = $this->assemble($key, $value);
                continue;
            }

            $newValue = $io->ask(sprintf("'%s' is not present into your %s file. Please enter a value :", $key, basename($destination)), $value);

            $destinationContent[] = $this->assemble($key, $newValue);
        }
        file_put_contents($destination, implode(PHP_EOL, $destinationContent));

        $io->writeln(sprintf("<info>The %s file is now synced</info>", basename($destination)));
    }

    private function guard(...$files)
    {
        foreach ($files as $file) {
            if (!File::exists($file)) {
                throw new FileNotFound(sprintf("%s must exists", $file));
            }
        }
    }

    /**
     * @return array
     */
    private function loadEnvFile($path)
    {
        $dotEnv = new EnvFileReader($path);
        return $dotEnv->load();
    }

    private function assemble($key, $value)
    {
        return sprintf("%s=%s", $key, $value);
    }
}