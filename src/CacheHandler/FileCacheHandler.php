<?php
namespace Slender\Config\CacheHandler;

use Slender\Config\Interfaces\CacheHandlerInterface;

class FileCacheHandler implements CacheHandlerInterface
{
    private $path;

    public function __construct($cacheFilePath)
    {
        $this->path = $cacheFilePath;
    }

    /**
     * Load a cached configuration, returning
     * an associative array of config values
     *
     * @return array
     */
    public function loadCache()
    {
        if (is_readable($this->path)) {
            return unserialize(file_get_contents($this->path));
        }
        return [];
    }

    /**
     * Cache an associative array of config values
     *
     * @param array $conf The config values
     * @return void
     */
    public function saveCache(array $conf)
    {
        file_put_contents($this->path, serialize($conf));
    }
}
