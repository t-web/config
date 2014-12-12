<?php

namespace Slender\Configurator\FileTypeAdapter;

use Slender\Configurator\Interfaces\FileTypeAdapterInterface;

/**
 * Class AbstractAdapter
 * @package Slender\Configurator\FileTypeAdapter
 */
abstract class AbstractAdapter implements FileTypeAdapterInterface
{
    /**
     * @var
     */
    protected $glob;

    /**
     * @param bool $recursive
     */
    public function __construct($recursive = false)
    {
        if ($recursive) {
            $this->glob = "**/".$this->glob;
        }
    }

    /**
     * @param $filePath
     * @return array
     */
    abstract public function parse($filePath);

    /**
     * Load configuration from a specified directory,
     * and return it as a nested array
     *
     * @param  string $dir Directory to load from
     * @return array  The configuration
     */
    public function loadFrom($dir)
    {
        $pattern = $dir.'/'.$this->glob;
        $files = glob($pattern);

        $conf = [];

        foreach ($files as $filePath) {
            $conf = array_merge_recursive($conf, $this->parse($filePath));
        }

        return $conf;
    }
}
