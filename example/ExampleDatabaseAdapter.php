<?php

use \Slender\Configurator\FileTypeAdapter\AbstractAdapter;
/**
 * Class ExampleDatabaseAdapter
 * @package Slender\Configurator\FileTypeAdapter
 */
class ExampleDatabaseAdapter extends AbstractAdapter
{
    /**
     * Load this over the top of any file configs
     *
     * @return array
     */
    public function getPostDirectoryConfig()
    {
        $db = new \mysqli();
        $r = $db->query("SELECT key,value FROM config");

        $conf = [];

        while( $row = $r->fetch_array(MYSQLI_NUM) ){
            $conf[$r[0]] = $r[1];
        }
        return $conf;
    }


}
