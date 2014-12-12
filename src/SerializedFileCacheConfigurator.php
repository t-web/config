<?php
/**
 * Created by PhpStorm.
 * User: alanp
 * Date: 12/12/14
 * Time: 12:51
 */

namespace Slender\Configurator;


use Slender\Configurator\Interfaces\ConfiguratorInterface;
use Slender\Configurator\Interfaces\FileTypeAdapterInterface;

class SerializedFileCacheConfigurator extends Configurator
{
    /** @var  string  */
    private $cacheFilePath;

    private $loadedFromCache = false;

    public function __construct($cacheFilePath, $rootPath = null, $env = null){
        $this->configurator = parent::__construct($rootPath, $env);
        $this->cacheFilePath = $cacheFilePath;

        // If the cache file exists, load it and we're all happy campers
        if(is_readable($cacheFilePath)){
            $this->merge(unserialize(file_get_contents($this->cacheFilePath)));
            $this->loadedFromCache = true;
        }
    }



    /**
     * Load config files from a directory.
     * The directory path is passed to each registered adapter in
     * the order they were registered.
     *
     * @param string $dir The directory to add
     * @return $this
     */
    public function addDirectory($dir)
    {
        // Only do the thing if we're not using a cached version
        if($this->loadedFromCache == false){
            return parent::addDirectory($dir);
        }
        return $this;
    }


    /**
     * Calling this method indicates all files have
     * been loaded and no more modifications are likely.
     *
     * This could be used to cached to loaded config to disk
     *
     * @return void
     */
    public function finalize()
    {
        if(!$this->loadedFromCache && $this->cacheFilePath){
            file_put_contents($this->cacheFilePath,
                serialize($this->toArray()));
        }
    }
}
