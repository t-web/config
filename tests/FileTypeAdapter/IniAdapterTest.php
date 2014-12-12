<?php

class IniAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testLoadFrom()
    {
        $adapter = new \Slender\Configurator\FileTypeAdapter\IniAdapter();
        $this->assertInternalType('array', $adapter->loadFrom('foo'));
    }
}
