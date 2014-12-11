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



use Slender\Configurator\Interfaces\FileTypeAdapterInterface;

/**
 * Class Configurator
 * @package Slender\Configurator
 */
class Configurator extends ConfigurationObject
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
     * Tracks the first load() call
     *
     * @var bool
     */
    protected $initialLoadDone = false;


    /**
     * @var FileTypeAdapterInterface[]
     */
    protected $fileTypeAdapters = [];


    /**
     * @param null $rootPath
     * @param null $env
     */
    public function __construct( $rootPath = null, $env = null){
        $this->setRootPath($rootPath);
        $this->setEnvironment($env);
    }



    /**
     * Add a source directory
     *
     * @param $dir
     * @return $this
     */
    public function addDirectory( $dir ){
        // Avoid duplicates
        if( in_array($dir, $this->directories)){
            return $this;
        }
        $this->directories[] = $dir;

        if($this->initialLoadDone){
            // Config already loaded... do the load for this dir separately...
        }

        return $this;
    }


    /**
     * @param FileTypeAdapterInterface $adapter
     * @return $this
     */
    public function addAdapter( FileTypeAdapterInterface $adapter )
    {
        if(!in_array($adapter,$this->fileTypeAdapters)) {
            $this->fileTypeAdapters[] = $adapter;
        }

        return $this;
    }



    /**
     * Wipe the config and load it all again
     *
     */
    public function load(){
        // Wipe any existing values
        $this->_config = [];

        // Loop through each directory
        foreach($this->directories as $dir){


            // Handle relative paths
            if(substr($dir,0,2) == './'){
                $dir = $this->getRootPath().substr($dir,2);
            }

            // Expand any placeholders
            $dir = self::replacePlaceholders($dir,[
                'ENVIRONMENT' => $this->getEnvironment()
            ]);

            // Remove any trailing slashes from dir
            $dir = rtrim($dir,'/');

            // Pass to each fileadapter in turn
            foreach($this->fileTypeAdapters as $adapter){
                $conf = $adapter->loadFrom($dir);
                $this->merge($conf);
            }
        }
    }


    /**
     * @param array $conf
     */
    public function merge( array $conf = array() ){
        $appConfig = &$this->_config;
        // Iterate through new top-level keys
        foreach ($conf as $key => $value) {
            // If doesnt exist yet, create it
            if (!isset($appConfig[$key])) {
                $appConfig[$key] = $value;
                continue;
            }
            // If it exists, and is already an array
            if (is_array($appConfig[$key])) {
                $mergedArray = array_merge_recursive($appConfig[$key], $value);
                $appConfig[$key] = $mergedArray;
                continue;
            }
            //@TODO check for iterators?
            // Just set the value already!
            $appConfig[$key] = $value;
        }
    }


    /**
     * @return array
     */
    public function toArray(){
        return $this->_config;
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
     * @param mixed $environment
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
     * @param string $rootPath
     * @return $this
     */
    public function setRootPath($rootPath)
    {
        $this->rootPath = rtrim($rootPath,'/').'/';
        return $this;
    }


    /**
     * Utility method to replace placeholders in a string
     *
     * @param $str
     * @param array $params
     * @return mixed
     */
    public static function replacePlaceholders($str, $params = [])
    {
        foreach($params as $key => $value){
            $key = '{'.$key.'}';
            $str = str_replace($key,$value,$str);
        }
        return $str;
    }


    /**
     * @param $arr1
     * @param $arr2
     * @return array
     */
    public static function mergeArrays( &$arr1, &$arr2 ){

        foreach($arr2 as $key => $val){
            if( !isset($arr1[$key]) ){
                // If key doesnt yet exist, just slap it in there
                if(gettype($val) == 'object'){
                    $val = (array) $val;
                }
                $arr1[$key] = $val;
            } else {
                // Key exists, do an intelligent merge
                if(gettype($arr1[$key]) == 'array'){
                    $arr1[$key] = self::mergeArrays($arr1[$key], $arr2[$key]);
                } else {
                    $arr1[$key] = $val;
                }
            }
        }

        return array_merge_recursive($arr1,$arr2);
    }


} 
