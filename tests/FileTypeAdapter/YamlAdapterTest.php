<?php

class YamlAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testLoadFrom()
    {
        $adapter = new \Slender\Configurator\FileTypeAdapter\YamlAdapter();
        $result = $adapter->loadFrom(__DIR__ . '/../config/');
        $this->assertInternalType('array', $result);
        $this->assertCount(2, $result);
        $this->assertEquals([
            'foo' => 'bar',
            'abc' => 'baz',
        ], $result);
    }
}
