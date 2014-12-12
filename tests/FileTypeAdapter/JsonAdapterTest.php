<?php

class JsonAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testLoadFrom()
    {
        $adapter = new \Slender\Configurator\FileTypeAdapter\JsonAdapter();
        $this->assertInternalType('array', $adapter->loadFrom('foo'));
    }
}
