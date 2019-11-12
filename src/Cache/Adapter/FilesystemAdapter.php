<?php

namespace App\Cache\Adapter;

use Symfony\Component\Cache\Adapter\FilesystemAdapter as BaseFilesystemAdapter;
use Symfony\Component\Cache\Marshaller\DefaultMarshaller;
use Symfony\Component\Cache\Marshaller\MarshallerInterface;
use Symfony\Component\Finder\Finder;

class FilesystemAdapter extends BaseFilesystemAdapter implements AdapterInterface
{
    private $directory;
    protected $marshaller;

    public function __construct(string $namespace = '', int $defaultLifetime = 0, string $directory = null, MarshallerInterface $marshaller = null)
    {
        $this->directory = $directory;
        $this->marshaller = $marshaller ?? new DefaultMarshaller();
        parent::__construct($namespace, $defaultLifetime, $directory, $marshaller);
    }

    public function getAllItems()
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in($this->directory)
        ;

        $now = time();

        foreach ($finder as $file) {
            $filePath = $file->getRealPath();
            if (!$h = @fopen($filePath, 'rb')) {
                continue;
            }
            if (($expiresAt = (int) fgets($h)) && $now >= $expiresAt) {
                fclose($h);
                @unlink($filePath);
            } else {
                $i = rawurldecode(rtrim(fgets($h)));
                $value = stream_get_contents($h);
                fclose($h);
                yield $this->marshaller->unmarshall($value);
            }
        }
    }
}