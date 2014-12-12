<?php

namespace Slender\Configurator\Interfaces;


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
     * @param array $conf  The config values
     * @return void
     */
    public function saveCache( array $conf );

} 
