<?php

class INITest extends PHPUnit_Framework_TestCase
{
    
    public function testLoadFrom()
    {
        $adapter = new \Slender\Configurator\FileTypeAdapter\INI();
        $this->assertInternalType('array',$adapter->loadFrom('foo'));
    }
}
