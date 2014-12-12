<?php

class ArrayAdapterTest extends PHPUnit_Framework_TestCase
{

    public function testLoadFrom()
    {
        $adapter = new \Slender\Configurator\FileTypeAdapter\PHP();
        $this->assertInternalType('array',$adapter->loadFrom('foo'));
    }
}
