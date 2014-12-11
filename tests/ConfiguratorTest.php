<?php

use Mockery as m;
use Slender\Configurator\Configurator;


/**
 * Class ConfiguratorTest
 * @package Slender\Configurator
 * @covers Slender\Configurator\Configurator
 */
class ConfiguratorTest extends \PHPUnit_Framework_TestCase
{


    public function tearDown()
    {
        m::close();
    }


    /**
     *
     */
    public function testSetEnvironment()
    {

        $ENV = 'my_env';

        $c = new Configurator();
        $c->setEnvironment($ENV);

        $refP = new \ReflectionProperty(get_class($c), 'environment');
        $refP->setAccessible(true);
        $value = $refP->getValue($c);

        $this->assertEquals($ENV, $value);
    }


    /**
     *
     */
    public function testGetEnvironment()
    {

        $c = new Configurator();
        $ENV = 'My_ENV';

        $refP = new \ReflectionProperty(get_class($c), 'environment');
        $refP->setAccessible(true);
        $refP->setValue($c, $ENV);

        $this->assertEquals($ENV, $c->getEnvironment());
    }


    /**
     *
     */
    public function testSetRootPath()
    {
        $c = new Configurator();
        $PATH = '/path/to/dir/';

        $c->setRootPath($PATH);

        $refP = new \ReflectionProperty(get_class($c), 'rootPath');
        $refP->setAccessible(true);
        $value = $refP->getValue($c);

        $this->assertEquals($PATH, $value);
    }


    /**
     *
     */
    public function testSetRootPathAddsTrailingSlash()
    {
        $c = new Configurator();
        $PATH = '/path/to/dir';

        $c->setRootPath($PATH);

        $refP = new \ReflectionProperty(get_class($c), 'rootPath');
        $refP->setAccessible(true);
        $value = $refP->getValue($c);

        $this->assertEquals($PATH . '/', $value);
    }

    /**
     *
     */
    public function testGetRootPath()
    {
        $c = new Configurator();
        $PATH = '/path/to/dir';

        $refP = new \ReflectionProperty(get_class($c), 'rootPath');
        $refP->setAccessible(true);
        $refP->setValue($c, $PATH);

        $this->assertEquals($PATH, $c->getRootPath());
    }


    /**
     * @covers Slender\Configurator\Configurator::addDirectory()
     */
    public function testAddDirectory()
    {
        $c = new Configurator();
        $DIR = "/path/to/dir";

        $returned = $c->addDirectory($DIR);

        $refP = new \ReflectionProperty(get_class($c), 'directories');
        $refP->setAccessible(true);
        $dirs = $refP->getValue($c);

        $this->assertEquals(1, count($dirs));
        $this->assertEquals($DIR, $dirs[0]);
        $this->assertEquals($c, $returned);

    }

    /**
     *
     */
    public function testAddDirectoryPreventsDuplicates()
    {
        $c = new Configurator();
        $DIR = "/path/to/dir";

        $c->addDirectory($DIR);
        $c->addDirectory($DIR);

        $refP = new \ReflectionProperty(get_class($c), 'directories');
        $refP->setAccessible(true);
        $dirs = $refP->getValue($c);

        $this->assertEquals(1, count($dirs));
        $this->assertEquals($DIR, $dirs[0]);

    }


    /**
     *
     */
    public function testAddAdapter()
    {
        $c = new Configurator();
        $adapter = m::mock('\Slender\Configurator\FileTypeAdapter\PHP');

        $returned = $c->addAdapter($adapter);

        $refP = new \ReflectionProperty(get_class($c), 'fileTypeAdapters');
        $refP->setAccessible(true);
        $adapters = $refP->getValue($c);

        $this->assertEquals(1, count($adapters));
        $this->assertEquals($adapter, $adapters[0]);
        $this->assertEquals($c, $returned);

    }


    public function testReplacePlaceholders()
    {
        $string = "/foo/{BAR}";
        $bar = "baz";

        $value = Configurator::replacePlaceholders($string,[
            'BAR' => $bar
        ]);

        $this->assertEquals("/foo/baz",$value);
    }



    public function testMergeWhenEmpty(){
        $c = new Configurator();
        $arr = [
            'foo' => 'bar'
        ];

        $c->merge($arr);
        $refP = new \ReflectionProperty(get_class($c), '_config');
        $refP->setAccessible(true);

        $value = $refP->getValue($c);

        $this->assertInternalType('array',$value);
        $this->assertArrayHasKey('foo',$value);
        $this->assertEquals('bar', $value['foo']);
        $this->assertEquals($arr,$value);


    }


    public function testMergeWhenNotEmpty(){
        $c = new Configurator();
        $arr = [
            'foo' => 'bar'
        ];

        $refP = new \ReflectionProperty(get_class($c), '_config');
        $refP->setAccessible(true);
        $refP->setValue($c,[
            'baz' => 'fwibble'
        ]);

        $c->merge($arr);
        $value = $refP->getValue($c);

        $this->assertInternalType('array',$value);
        $this->assertArrayHasKey('foo',$value);
        $this->assertEquals('bar', $value['foo']);
        $this->assertArrayHasKey('baz',$value);
        $this->assertEquals('fwibble', $value['baz']);
        $this->assertEquals(['foo'=>'bar','baz'=>'fwibble'],$value);

    }


    public function testMergeOverwritesScalarValues(){
        $c = new Configurator();
        $arr = [
            'foo' => 'success'
        ];

        $refP = new \ReflectionProperty(get_class($c), '_config');
        $refP->setAccessible(true);
        $refP->setValue($c,[
            'foo' => 'fail'
        ]);

        $c->merge($arr);
        $value = $refP->getValue($c);

        $this->assertInternalType('array',$value);
        $this->assertArrayHasKey('foo',$value);
        $this->assertEquals('success', $value['foo']);
        $this->assertEquals(['foo'=>'success'], $value);

    }


    public function testMergeMergesArrayValues(){
        $c = new Configurator();
        $arr = [
            'foo' => [ 3,4 ]
        ];

        $refP = new \ReflectionProperty(get_class($c), '_config');
        $refP->setAccessible(true);
        $refP->setValue($c,[
            'foo' => [1,2]
        ]);

        $c->merge($arr);
        $value = $refP->getValue($c);

        $this->assertInternalType('array',$value);
        $this->assertArrayHasKey('foo',$value);
        $this->assertCount(4, $value['foo']);
        $this->assertEquals([1,2,3,4], $value['foo']);

    }



    public function testToArrayWorks(){
        $c = new Configurator();
        $arr = [
            'foo' => [1,2],
            'baz' => 123
        ];

        $refP = new \ReflectionProperty(get_class($c), '_config');
        $refP->setAccessible(true);
        $refP->setValue($c,$arr);


        $value = $c->toArray();

        $this->assertEquals($arr, $value);
    }


    /**
     * @covers Slender\Configurator\Configurator::load
     */
    public function testLoadCallsAdapterMerge(){

        $c = new Configurator();
        $c->addDirectory('/foo/bar');
        $c->addDirectory('./foo/bar');
        $arr = [
            'foo' => 'bar'
        ];

        $adapter = m::mock("Slender\\Configurator\\FileTypeAdapter\\PHP");
        $adapter->shouldReceive('loadFrom')
            ->times(2)
            ->andReturn($arr);

        $c->addAdapter($adapter);
        $c->load();

    }



}
