<?php

use Slender\Configurator\CacheHandler\FileCacheHandler;

class FileCacheHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FileCacheHandler;
     */
    protected $handler;

    /**
     * @var string
     */
    protected $filePath;


    public function setUp()
    {
        $this->filePath = getcwd() . '/foo.tmp';
        touch($this->filePath);
        $this->handler = new FileCacheHandler($this->filePath);

    }

    public function tearDown()
    {
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }

    public function testConstructorSetsFilePathFromFirstArgument()
    {

        $pathProperty = new ReflectionProperty("Slender\\Configurator\\CacheHandler\\FileCacheHandler", "path");
        $pathProperty->setAccessible(true);

        $handler = new FileCacheHandler($this->filePath);

        $value = $pathProperty->getValue($handler);

        $this->assertNotNull($value);
        $this->assertInternalType('string', $value);
        $this->assertEquals($this->filePath, $value);
    }


    public function testLoadCache()
    {
        $data = ['foo' => 'bar'];

        file_put_contents($this->filePath, serialize($data));

        $value = $this->handler->loadCache();
        $this->assertNotNull($value);
        $this->assertInternalType('array', $value);
        $this->assertEquals($data, $value);
    }


    public function testLoadCacheReturnsEmptyArrayIfSourceFileNotReadable()
    {
        $pathProperty = new ReflectionProperty("Slender\\Configurator\\CacheHandler\\FileCacheHandler", "path");
        $pathProperty->setAccessible(true);

        $pathProperty->setValue($this->handler, '/invalid/file/path');

        $value = $this->handler->loadCache();

        $this->assertNotNull($value);
        $this->assertInternalType('array', $value);
        $this->assertEmpty($value);
    }


    public function testSaveCacheSavesToDisk()
    {
        $data = ['foo' => 'bar'];
        $serialized = serialize($data);

        $this->handler->saveCache($data);

        $saved = file_get_contents($this->filePath);

        $this->assertEquals($serialized, $saved);


    }

}
