<?php

class ArrayAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testLoadFrom()
    {
        $adapter = new \Slender\Configurator\FileTypeAdapter\ArrayAdapter();
        $this->assertInternalType('array', $adapter->loadFrom('foo'));
    }
}
