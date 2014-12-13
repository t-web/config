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
namespace Slender\Configurator\Interfaces;


interface ConfigInterface
{
    /**
     * Set the root directory for relative paths
     *
     * @param string $rootPath Root directory for relative paths
     * @return $this
     */
    public function setRootPath($rootPath);

    /**
     * Returns the defined root directory
     *
     * @return string
     */
    public function getRootPath();

    /**
     * Set the environment name for path expansion
     *
     * @param string $env Environment name
     * @return $this
     */
    public function setEnvironment($env);

    /**
     * Returns the defined environment name
     *
     * @return string
     */
    public function getEnvironment();

    /**
     * Add a filetype adapter to use
     *
     * @param FileTypeAdapterInterface $adapter
     * @return $this
     */
    public function addAdapter(FileTypeAdapterInterface $adapter);

    /**
     * Load config files from a directory.
     * The directory path is passed to each registered adapter in
     * the order they were registered.
     *
     * @param string $dir The directory to add
     * @return $this
     */
    public function addDirectory($dir);

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
    public function setCacheHandler(CacheHandlerInterface $cacheHandler);

    /**
     * Returns the registered cache handler
     *
     * @return CacheHandlerInterface|null
     */
    public function getCacheHandler();

    /**
     * Merge an array of values into the configuration
     *
     * @param array $conf
     * @return $this
     */
    public function merge(array $conf = []);

    /**
     * Return a simple array representation of the
     * configuration
     *
     * @return array
     */
    public function toArray();

    /**
     * Calling this method indicates all files have
     * been loaded and no more modifications are likely.
     *
     * This could be used to cached to loaded config to disk
     *
     * @return void
     */
    public function finalize();
} 
