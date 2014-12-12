<?php
/**
 * Slender Configurator
 *
 * @author      Alan Pich <alan.pich@gmail.com>
 * @copyright   2015 Alan Pich
 * @link        http://github.com/alanpich/Slender-Configurator
 * @license     http://github.com/alanpich/Slender-Configurator/blob/master/LICENSE
 * @package     Slender\Configurator
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Slender\Configurator;

use Slender\Configurator\Interfaces\CacheHandlerInterface;
use Slender\Configurator\Interfaces\ConfigInterface;
use Slender\Configurator\Interfaces\FileTypeAdapterInterface;

/**
 * Class Configurator
 *
 * @package Slender\Configurator
 */
class Config extends ConfigurationObject
    implements ConfigInterface
{
    /**
     * @var string
     */
    private $rootPath;

    /**
     * @var string
     */
    private $environment;

    /**
     * @var string[]
     */
    private $directories = [];

    /**
     * @var FileTypeAdapterInterface[]
     */
    protected $fileTypeAdapters = [];


    /** @var  CacheHandlerInterface */
    protected $cacheHandler;

    /**
     * @var bool
     */
    protected $wasLoadedFromCache = false;

    /**
     * @param null $rootPath
     * @param null $env
     */
    public function __construct($rootPath = null, $env = null, $cacheHandler = null)
    {
        $this->setRootPath($rootPath);
        $this->setEnvironment($env);
        $this->setCacheHandler($cacheHandler);
    }

    /**
     * @param  FileTypeAdapterInterface $adapter
     * @return $this
     */
    public function addAdapter(FileTypeAdapterInterface $adapter)
    {
        if (!in_array($adapter, $this->fileTypeAdapters)) {
            $this->fileTypeAdapters[] = $adapter;
        }
        return $this;
    }
    
    /**
     * Add a source directory
     *
     * @param $dir
     * @return $this
     */
    public function addDirectory($dir)
    {
        // Have we already loaded a cached version?
        if (!$this->wasLoadedFromCache) {

            // Avoid duplicates
            if (!in_array($dir, $this->directories)) {
                $this->directories[] = $dir;
            }

            // Handle relative paths
            if (substr($dir, 0, 2) == './') {
                $dir = $this->getRootPath() . substr($dir, 2);
            }

            // Expand any placeholders
            $dir = self::replacePlaceholders($dir, [
                'ENVIRONMENT' => $this->getEnvironment()
            ]);

            // Remove any trailing slashes from dir
            $dir = rtrim($dir, '/');

            // Pass to each fileadapter in turn
            foreach ($this->fileTypeAdapters as $adapter) {
                $conf = $adapter->loadFrom($dir);
                $this->merge($conf);
            }
        }

        return $this;
    }
    
    /**
     * Sets a cache handler, and loads the cached
     * values from it, merging them into config.
     *
     * NOTE: While many cache handlers can be used
     *       to load from, only the _last_ registered
     *       handler will be used to store the cache
     *       when finalize() is called
     *
     * @param CacheHandlerInterface $cacheHandler
     * @return $this
     */
    public function setCacheHandler($cacheHandler)
    {
        $this->cacheHandler = $cacheHandler;

        // If a real handler, load the cache
        if (!is_null($this->cacheHandler)) {
            $cachedConf = $this->cacheHandler->loadCache();
            if (!empty($cachedConf)) {
                $this->merge($this->cacheHandler->loadCache());
                $this->wasLoadedFromCache = true;
            }
        }

        return $this;
    }
    
    /**
     * Finalize the configuration.
     *
     * This is the point where the configuration is
     * passed to the registered CacheAdapterInterface to
     * be stored.
     *
     * NOTE: Any directories added _after_ `finalize()` is
     *       called _will_ be merged into the config, but
     *       _won't_ be cached. Useful for runtime-specific
     *       configuration overrides.
     */
    public function finalize()
    {
        if (!is_null($this->cacheHandler)) {
            $this->cacheHandler->saveCache($this->toArray());
        }
        $this->wasLoadedFromCache = false;
    }

    /**
     * @param array $conf
     * @return $this
     */
    public function merge(array $conf = [])
    {
        $this->config = self::mergeArrays($this->config, $conf);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->config;
    }

    /**
     * Get the currently defined environment
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Set the enviroment name
     *
     * @param  mixed $environment
     * @return $this
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     * Get the defined root path for relative urls
     *
     * @return string
     */
    public function getRootPath()
    {
        return $this->rootPath;
    }

    /**
     * Set the root path for relative urls
     *
     * @param  string $rootPath
     * @return $this
     */
    public function setRootPath($rootPath)
    {
        $this->rootPath = rtrim($rootPath, '/') . '/';

        return $this;
    }

    /**
     * Utility method to replace placeholders in a string
     *
     * @param $str
     * @param  array $params
     * @return mixed
     */
    public static function replacePlaceholders($str, $params = [])
    {
        foreach ($params as $key => $value) {
            $key = '{' . $key . '}';
            $str = str_replace($key, $value, $str);
        }

        return $str;
    }

    /**
     * @return CacheHandlerInterface
     */
    public function getCacheHandler()
    {
        return $this->cacheHandler;
    }
    
    public static function mergeArrays( $arr1, $arr2)
    {
        $merged = array_merge([],$arr1);

        // Iterate through new top-level keys
        foreach ($arr2 as $key => $value) {
            // If doesn't exist yet, create it
            if (!isset($merged[$key])) {
                $merged[$key] = $value;
                continue;
            }
            // If it exists, and is already an array
            if (is_array($merged[$key])) {
                if(is_numeric(array_keys($value)[0])){
                    // Append
                    $merged[$key] = array_merge_recursive($merged[$key],$value);
                } else {
                    $merged[$key] = self::mergeArrays($merged[$key], $value);
                }
                continue;
            }
            //@TODO check for iterators?
            // Just set the value already!
            $merged[$key] = $value;
        }

        return $merged;
    }
}