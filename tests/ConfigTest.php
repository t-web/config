<?php

use Mockery as m;
use Slender\Configurator\Config;

/**
 * Class ConfiguratorTest
 * @package Slender\Configurator
 * @covers Slender\Configurator\Config
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var ReflectionProperty
     */
    protected $environmentProperty;

    /**
     * @var ReflectionProperty
     */
    protected $rootPathProperty;

    /**
     * @var ReflectionProperty
     */
    protected $directoriesProperty;

    /**
     * @var ReflectionProperty
     */
    protected $dataProperty;

    /**
     * @var ReflectionProperty
     */
    protected $adaptersProperty;

    /**
     * @var ReflectionProperty
     */
    protected $cacheHandlerProperty;

    protected function setUp()
    {
        $this->config = new Config();
        $this->environmentProperty = new \ReflectionProperty($this->config, 'environment');
        $this->environmentProperty->setAccessible(true);

        $this->rootPathProperty = new \ReflectionProperty($this->config, 'rootPath');
        $this->rootPathProperty->setAccessible(true);

        $this->directoriesProperty = new \ReflectionProperty($this->config, 'directories');
        $this->directoriesProperty->setAccessible(true);

        $this->dataProperty = new \ReflectionProperty($this->config, 'data');
        $this->dataProperty->setAccessible(true);

        $this->adaptersProperty = new \ReflectionProperty($this->config, 'fileTypeAdapters');
        $this->adaptersProperty->setAccessible(true);

        $this->cacheHandlerProperty = new \ReflectionProperty($this->config, 'cacheHandler');
        $this->cacheHandlerProperty->setAccessible(true);
    }

    public function tearDown()
    {
        m::close();
    }


    public function testConstructorAddsOptionalCacheHandler()
    {
        $dummyCacheHandler = Mockery::mock("Slender\\Configurator\\CacheHandler\\FileCacheHandler");
        $dummyCacheHandler
            ->shouldReceive("loadCache")
            ->once();
        $config = new Config(null, null, $dummyCacheHandler);


        $this->assertNotNull($this->cacheHandlerProperty->getValue($config));
        $this->assertInstanceOf('Slender\Configurator\Interfaces\CacheHandlerInterface',
            $this->cacheHandlerProperty->getValue($config));
        $this->assertEquals($dummyCacheHandler, $this->cacheHandlerProperty->getValue($config));

    }


    public function testSetEnvironment()
    {
        $ENV = 'my_env';

        $this->config->setEnvironment($ENV);
        $this->assertEquals($ENV, $this->environmentProperty->getValue($this->config));
    }

    public function testGetEnvironment()
    {
        $ENV = 'My_ENV';

        $this->environmentProperty->setValue($this->config, $ENV);
        $this->assertEquals($ENV, $this->config->getEnvironment());
    }

    public function testSetRootPath()
    {
        $PATH = '/path/to/dir/';

        $this->config->setRootPath($PATH);
        $this->assertEquals($PATH, $this->rootPathProperty->getValue($this->config));
    }

    public function testSetRootPathAddsTrailingSlash()
    {
        $PATH = '/path/to/dir';

        $this->config->setRootPath($PATH);
        $this->assertEquals($PATH . '/', $this->rootPathProperty->getValue($this->config));
    }

    public function testGetRootPath()
    {
        $PATH = '/path/to/dir';

        $this->rootPathProperty->setValue($this->config, $PATH);
        $this->assertEquals($PATH, $this->config->getRootPath());
    }

    /**
     * @covers Slender\Configurator\Config::addDirectory()
     */
    public function testAddDirectory()
    {
        $DIR = "/path/to/dir";

        $returned = $this->config->addDirectory($DIR);

        $dirs = $this->directoriesProperty->getValue($this->config);
        $this->assertEquals(1, count($dirs));
        $this->assertEquals($DIR, $dirs[0]);
        $this->assertEquals($this->config, $returned);

        $DIR2 = './path';
        $returned = $this->config->addDirectory($DIR2);
        $dirs = $this->directoriesProperty->getValue($this->config);
        $this->assertEquals(2, count($dirs));
        $this->assertEquals($DIR2, $dirs[1]);
        $this->assertEquals($this->config, $returned);
    }

    public function testAddDirectoryPreventsDuplicates()
    {
        $DIR = "/path/to/dir";

        $this->config->addDirectory($DIR);
        $this->config->addDirectory($DIR);
        $dirs = $this->directoriesProperty->getValue($this->config);

        $this->assertEquals(1, count($dirs));
        $this->assertEquals($DIR, $dirs[0]);
    }

    public function testAddDirectoryDummyToSatisfyExpandsRelativeUrls()
    {
        $RELATIVE_DIR = './relative/path/to/dir';

        $this->config->addDirectory($RELATIVE_DIR);
    }

    public function testAddDirectoryCallsAdapterLoadFrom()
    {
        $DIR = '/path/to/dir';
        $dummyAdapter = Mockery::mock("Slender\\Configurator\\Interfaces\\FileTypeAdapterInterface");

        $this->directoriesProperty->setValue($this->config, [$DIR]);

        $dummyAdapter
            ->shouldReceive('loadFrom')
            ->with($DIR)
            ->once()
            ->andReturn([]);
        $this->adaptersProperty->setValue($this->config, [$dummyAdapter]);

        $this->config->addDirectory($DIR);

    }

    public function testAddAdapter()
    {
        $adapter = m::mock('\Slender\Configurator\FileTypeAdapter\ArrayAdapter');

        $returned = $this->config->addAdapter($adapter);

        $refP = new \ReflectionProperty($this->config, 'fileTypeAdapters');
        $refP->setAccessible(true);
        $adapters = $refP->getValue($this->config);

        $this->assertEquals(1, count($adapters));
        $this->assertEquals($adapter, $adapters[0]);
        $this->assertEquals($this->config, $returned);
    }

    public function testReplacePlaceholders()
    {
        $string = "/foo/{BAR}";
        $bar = "baz";

        $value = Config::replacePlaceholders($string, [
            'BAR' => $bar
        ]);

        $this->assertEquals("/foo/baz", $value);
    }

    public function testMergeWhenEmpty()
    {
        $data = [
            'foo' => 'bar',
        ];

        $this->config->merge($data);
        $value = $this->dataProperty->getValue($this->config);

        $this->assertInternalType('array', $value);
        $this->assertArrayHasKey('foo', $value);
        $this->assertEquals('bar', $value['foo']);
        $this->assertEquals($data, $value);
    }

    public function testMergeWhenNotEmpty()
    {
        $data = [
            'foo' => 'bar',
        ];

        $this->dataProperty->setValue($this->config, [
            'baz' => 'fwibble'
        ]);

        $this->config->merge($data);
        $value = $this->dataProperty->getValue($this->config);

        $this->assertInternalType('array', $value);
        $this->assertArrayHasKey('foo', $value);
        $this->assertEquals('bar', $value['foo']);
        $this->assertArrayHasKey('baz', $value);
        $this->assertEquals('fwibble', $value['baz']);
        $this->assertEquals(['foo' => 'bar', 'baz' => 'fwibble'], $value);
    }

    public function testMergeOverwritesScalarValues()
    {
        $data = [
            'foo' => 'success',
        ];

        $this->dataProperty->setValue($this->config, [
            'foo' => 'fail'
        ]);

        $this->config->merge($data);
        $value = $this->dataProperty->getValue($this->config);

        $this->assertInternalType('array', $value);
        $this->assertArrayHasKey('foo', $value);
        $this->assertEquals('success', $value['foo']);
        $this->assertEquals(['foo' => 'success'], $value);
    }

    public function testMergeMergesArrayValues()
    {
        $data = [
            'foo' => [3, 4],
        ];

        $this->dataProperty->setValue($this->config, [
            'foo' => [1, 2]
        ]);

        $this->config->merge($data);
        $value = $this->dataProperty->getValue($this->config);

        $this->assertInternalType('array', $value);
        $this->assertArrayHasKey('foo', $value);
        $this->assertCount(4, $value['foo']);
        $this->assertEquals([1, 2, 3, 4], $value['foo']);
    }

    public function testToArrayWorks()
    {
        $data = [
            'foo' => [1, 2],
            'baz' => 123,
        ];

        $this->dataProperty->setValue($this->config, $data);
        $this->assertEquals($data, $this->config->toArray());
    }
}
