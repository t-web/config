<?php

class IniAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testLoadFrom()
    {
        $adapter = new \Slender\Config\FileTypeAdapter\IniAdapter();
        $this->assertInternalType('array', $adapter->loadFrom('foo'));
    }

    public function testParse()
    {
        $adapter = new \Slender\Config\FileTypeAdapter\IniAdapter();
        $result = $adapter->parse(__DIR__ . '/../config/config.ini');

        $this->assertCount(2, $result);

        $this->assertArrayHasKey('foo', $result);
        $this->assertEquals('bar', $result['foo']);

        $this->assertArrayHasKey('subfoo', $result);
        $this->assertCount(1, $result['subfoo']);
        $this->assertArrayHasKey('baz', $result['subfoo']);
        $this->assertEquals('fwibble', $result['subfoo']['baz']);
        $this->assertEquals([
            'foo' => 'bar',
            'subfoo' => [
                'baz' => 'fwibble',
            ],
        ], $result);
    }
}
