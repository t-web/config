<?php

class JSONTest extends PHPUnit_Framework_TestCase
{
    
    public function testLoadFrom()
    {
        $adapter = new \Slender\Configurator\FileTypeAdapter\JSON();
        $this->assertInternalType('array',$adapter->loadFrom('foo'));
    }
}
