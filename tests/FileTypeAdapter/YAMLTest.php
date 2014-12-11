<?php

class YAMLTest extends PHPUnit_Framework_TestCase
{
    
    public function testLoadFrom()
    {
        $adapter = new \Slender\Configurator\FileTypeAdapter\YAML();
        $this->assertInternalType('array',$adapter->loadFrom('foo'));
    }
}
