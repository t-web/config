<?php

use Slender\Configurator\Collection;

class CollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Collection
     */
    protected $bag;

    /**
     * @var ReflectionProperty
     */
    protected $property;

    public function setUp()
    {
        $this->bag = new Collection();
        $this->property = new ReflectionProperty($this->bag, "data");
        $this->property->setAccessible(true);
    }

    public function testGet()
    {
        $this->property->setValue($this->bag, ['foo' => 'bar']);
        $this->assertEquals('bar', $this->bag->get('foo'));
    }

    public function testSet()
    {
        $this->bag->set('foo', 'bar');
        $this->assertArrayHasKey('foo', $this->property->getValue($this->bag));
        $configObject = $this->property->getValue($this->bag);
        $this->assertEquals('bar', $configObject['foo']);
    }

    public function testHas()
    {
        $this->property->setValue($this->bag, ['foo' => 'bar']);
        $this->assertTrue($this->bag->has('foo'));
        $this->assertFalse($this->bag->has('abc'));
    }

    public function testGetWithDefault()
    {
        $this->property->setValue($this->bag, ['foo' => 'bar']);
        $this->assertEquals('default', $this->bag->get('abc', 'default'));
    }

    public function testRemove()
    {
        $this->property->setValue($this->bag, ['foo' => 'bar']);
        $this->bag->remove('foo');
        $this->assertEquals([], $this->property->getValue($this->bag));
    }

    public function testReplace()
    {
        $data = [
            'foo' => 'bar',
            'abc' => 123
        ];
        $this->property->setValue($this->bag, $data);
        $this->assertEquals($data, $this->property->getValue($this->bag));
        $this->bag->replace(['foo' => 'baz']);
        $this->assertEquals(['foo' => 'baz', 'abc' => 123], $this->property->getValue($this->bag));
    }

    public function testCount()
    {
        $this->property->setValue($this->bag, ['foo' => 'bar', 'abc' => '123']);
        $this->assertEquals(2, $this->bag->count());
    }

    public function testOffsetExists()
    {
        $this->property->setValue($this->bag, ['foo' => 'bar']);

        $this->assertTrue($this->bag->offsetExists('foo'));
        $this->assertTrue(isset($this->bag['foo']));
    }

    public function testOffsetGet()
    {
        $this->property->setValue($this->bag, ['foo' => 'bar']);

        $this->assertEquals('bar', $this->bag->offsetGet('foo'));
        $this->assertEquals('bar', $this->bag['foo']);
        $this->assertNull($this->bag->offsetGet('nonexistant'));
        $this->assertNull($this->bag['nonexistant']);
    }

    public function testOffsetSet()
    {
        $this->bag->offsetSet('foo', 'bar');
        $this->bag['baz'] = 'fwibble';

        $data = $this->property->getValue($this->bag);

        $this->assertEquals('bar', $data['foo']);
        $this->assertEquals('fwibble', $data['baz']);
    }

    public function testOffsetUnset()
    {
        $this->property->setValue($this->bag, ['foo' => 'bar']);

        $this->bag->offsetUnset('foo');

        $val = $this->property->getValue($this->bag);

        $this->assertEmpty($val);
        $this->assertFalse(isset($val['foo']));
    }
}
