<?php

class YamlAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testLoadFrom()
    {
        $adapter = new \Slender\Configurator\FileTypeAdapter\YamlAdapter();
        $this->assertInternalType('array', $adapter->loadFrom('foo'));
    }
}
