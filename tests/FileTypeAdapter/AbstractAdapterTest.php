<?php

use Slender\Config\FileTypeAdapter\AbstractAdapter;


class ExampleAdapter extends AbstractAdapter
{
    protected $glob = '*.foo';

    public function parse($filePath){

    }
}



class AbstractAdapterTest extends \PHPUnit_Framework_TestCase
{

    /** @var  ReflectionProperty */
    protected $globProperty;


    public function setUp()
    {
        $this->globProperty = new ReflectionProperty("ExampleAdapter","glob");
        $this->globProperty->setAccessible(true);
    }



    public function testConstructorHandlesRecursiveFlagArgument()
    {
        $a = new ExampleAdapter();
        $b = new ExampleAdapter(true);

        $this->assertEquals('*.foo', $this->globProperty->getValue($a));
        $this->assertEquals('**/*.foo',$this->globProperty->getValue($b));

    }


    public function testLoadFromReturnsEmptyArrayWithNullGlob()
    {
        $adapter = new ExampleAdapter();
        $this->globProperty->setValue($adapter, null);

        $result = $adapter->loadFrom('irrellevant_argument');

        $this->assertInternalType('array',$result);
        $this->assertEmpty($result);
    }

}
