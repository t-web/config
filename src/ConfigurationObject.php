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

/**
 * Class ConfigurationObject
 * @package Slender\Configurator
 */
class ConfigurationObject implements \ArrayAccess
{
    /**
     * @var array
     */
    protected $config = [];

    //////////////////////////////////////////////////////////////////
    // Object Property Access
    //////////////////////////////////////////////////////////////////

    /**
     * @param $offset
     * @param null $default
     * @return mixed
     */
    public function get($offset, $default = null)
    {
        if (!$this->has($offset)) {
            return $default;
        }
        return $this->config[$offset];
    }

    /**
     * @param $offset
     * @return bool
     */
    public function has($offset)
    {
        return isset($this->config[$offset]);
    }

    /**
     * @param $offset
     * @param $value
     */
    public function set($offset, $value)
    {
        $this->config[$offset] = $value;
    }

    /**
     * @param $offset
     */
    public function remove($offset)
    {
        unset($this->config[$offset]);
    }

    /**
     * @param $offset
     * @return mixed
     */
    public function __get($offset)
    {
        return $this->get($offset);
    }

    //////////////////////////////////////////////////////////////////
    // ArrayAccess Access
    //////////////////////////////////////////////////////////////////

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}
