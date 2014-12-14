<?php

class ArrayAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testLoadFrom()
    {
        $adapter = new \Slender\Configurator\FileTypeAdapter\ArrayAdapter();
        $this->assertInternalType('array', $adapter->loadFrom('foo'));
    }

    public function testParse()
    {
        $adapter = new \Slender\Configurator\FileTypeAdapter\ArrayAdapter();
        $result = $adapter->parse(__DIR__ . '/../config/config.php');
        $this->assertInternalType('array', $result);
        $this->assertCount(2, $result);
        $this->assertEquals([
            'foo' => 'bar',
            'abc' => 'baz',
        ], $result);
    }
}
