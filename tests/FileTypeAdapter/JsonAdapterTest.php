<?php

class JsonAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testLoadFrom()
    {
        $adapter = new \Slender\Config\FileTypeAdapter\JsonAdapter();
        $this->assertInternalType('array', $adapter->loadFrom('foo'));
    }

    public function testParse()
    {
        $adapter = new \Slender\Config\FileTypeAdapter\JsonAdapter();
        $result = $adapter->parse(__DIR__ . '/../config/config.json');
        $this->assertInternalType('array', $result);
        $this->assertCount(2, $result);
        $this->assertEquals([
            'foo' => 'bar',
            'abc' => 'baz',
        ], $result);
    }
}
