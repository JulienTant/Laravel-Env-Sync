<?php
namespace Jtant\LaravelEnvSync;

use File;
use Symfony\Component\Console\Output\OutputInterface;

class SyncService
{

    public function sync(OutputInterface $io, $source, $dest)
    {
        try {
            self::guard($source, $dest);
        } catch (FileNotFound $exception) {
            $io->writeln($exception->getMessage());
        }

        $destValues = self::loadEnvFile($dest);
        $sourceValues = self::loadEnvFile($source);

        $destContent = [];
        foreach ($sourceValues as $key => $value) {
            if (array_key_exists($key, $destValues)) {
                $destContent[] = $this->assemble($key, $value);
                continue;
            }

            $newValue = $io->ask(sprintf("'%s' is not present into your .env file. Please enter a value :", $key), $value);

            $destContent[] = $this->assemble($key, $newValue);
        }
        file_put_contents($dest, implode(PHP_EOL, $destContent));

        $io->writeln("<info>The .env file is now synced</info>");
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