<?php

namespace App\Tests\Traits;

use Symfony\Component\Filesystem\Exception\InvalidArgumentException;
use Symfony\Component\Filesystem\Filesystem;

trait TemporarySpaceTrait
{
    protected function tempSpaceUp(string $namespace): string
    {
        $path = sys_get_temp_dir() . '/symfony-cache/' . $namespace;

        if (!is_dir($path) && !@mkdir($path, 0777, true)) {
            throw new InvalidArgumentException(sprintf('Directory does not exist and cannot be created: %s.', $path));
        }

        if (!is_writable($path)) {
            throw new InvalidArgumentException(sprintf('Directory is not writable: %s.', $path));
        }

        $path = realpath($path);

        return $path;
    }

    protected function tempSpaceDown(string $path)
    {
        $filesystem = new Filesystem();
        $filesystem->remove($path);
    }

}