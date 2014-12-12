<?php

use Slender\Configurator\ConfigurationObject;

class ConfigurationObjectTest extends PHPUnit_Framework_TestCase
{
    public function get_configValue(ConfigurationObject $o)
    {
        $refP = new ReflectionProperty("Slender\\Configurator\\ConfigurationObject", "config");
        $refP->setAccessible(true);

        return $refP->getValue($o);
    }

    public function set_configValue(ConfigurationObject $o, $value)
    {
        $refP = new ReflectionProperty("Slender\\Configurator\\ConfigurationObject", "config");
        $refP->setAccessible(true);

        return $refP->setValue($o, $value);
    }

    public function testOffsetExists()
    {
        $o = new ConfigurationObject();
        $arr = ['foo' => 'bar'];

        $this->set_configValue($o, $arr);

        $this->assertTrue($o->offsetExists('foo'));
        $this->assertTrue(isset($o['foo']));
    }

    public function testOffsetGet()
    {
        $o = new ConfigurationObject();
        $arr = ['foo' => 'bar'];

        $this->set_configValue($o, $arr);

        $this->assertEquals('bar', $o->offsetGet('foo'));
        $this->assertEquals('bar', $o['foo']);
        $this->assertNull($o->offsetGet('nonexistant'));
        $this->assertNull($o['nonexistant']);
    }

    public function testOffsetSet()
    {
        $o = new ConfigurationObject();

        $o->offsetSet('foo', 'bar');
        $o['baz'] = 'fwibble';

        $arr = $this->get_configValue($o);

        $this->assertEquals('bar', $arr['foo']);
        $this->assertEquals('fwibble', $arr['baz']);
    }

    public function testOffsetUnset()
    {
        $o = new ConfigurationObject();
        $arr = ['foo' => 'bar'];
        $this->set_configValue($o, $arr);

        $o->offsetUnset('foo');

        $val = $this->get_configValue($o);

        $this->assertEmpty($val);
        $this->assertFalse(isset($val['foo']));
    }

    public function testGetter()
    {
        $o = new ConfigurationObject();
        $arr = ['foo' => 'bar'];
        $this->set_configValue($o, $arr);

        $this->assertEquals('bar', $o->foo);
        $this->assertNull($o->nonexistant_property);
    }
}
