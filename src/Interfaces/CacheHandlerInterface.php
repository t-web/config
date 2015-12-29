<?php
/**
 * Slender Config
 *
 * @author      Alan Pich <alan.pich@gmail.com>
 * @copyright   2015 Alan Pich
 * @link        http://github.com/slenderphp/config
 * @license     http://github.com/slenderphp/config/blob/master/LICENSE
 * @package     Slender\Config
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
namespace Slender\Config\Interfaces;


/**
 * Defines an interface for configuration caching mechanisms
 *
 *   Cache Handlers have two methods, `load()` and `save()`.
 *   These should be fairly self-explanatory, which return
 *   and accept an associative array of configuration values.
 *
 * @package Slender\Config\Interfaces
 */
interface CacheHandlerInterface
{
    /**
     * Load a cached configuration, returning
     * an associative array of config values
     *
     * @return array
     */
    public function loadCache();

    /**
     * Cache an associative array of config values
     *
     * @param array $conf The config values
     * @return void
     */
    public function saveCache(array $conf);
} 
