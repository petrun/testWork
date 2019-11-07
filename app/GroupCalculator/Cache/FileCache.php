<?php

namespace App\GroupCalculator\Cache;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class FileCache implements Cache
{
    private $path;
    private $finder;
    private $filesystem;

    public function __construct($path)
    {
        $this->path = $path;
        $this->filesystem = new Filesystem();

//        $this->finder = new Finder();
//        $finder->files()->in($path);
//        $this->finder = $finder;
    }

    private function getKeyPath($key)
    {
        return $this->path . '/' . $key . '.txt';
    }

    public function get($key)
    {
        $path = $this->getKeyPath($key);
        if($this->filesystem->exists($path)){
            return file_get_contents($path);
        }
    }

    public function set($key, $value)
    {
//        dd($value);
        $path = $this->getKeyPath($key);
        file_put_contents($path, $value);
        dd('set');
        //file_put_contents
    }

    public function getAll():array
    {
        //get all files
        //foreach
    }

    public function clear()
    {
        //delete all files
    }

}