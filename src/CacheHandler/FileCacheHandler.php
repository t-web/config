<?php
/**
 * Created by PhpStorm.
 * User: alanp
 * Date: 12/12/14
 * Time: 13:27
 */

namespace Slender\Configurator\CacheHandler;


use Slender\Configurator\Interfaces\CacheHandlerInterface;

class FileCacheHandler implements CacheHandlerInterface
{

    private $path;

    public function __construct( $cacheFilePath )
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
        if(is_readable($this->path)){
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
        file_put_contents($this->path,serialize($conf));
    }
}
