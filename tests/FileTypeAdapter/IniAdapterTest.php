<?php

$_EXAMPLE_INI = <<<EOF
foo = bar

[subfoo]
baz = fwibble

EOF;


class IniAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testLoadFrom()
    {
        $adapter = new \Slender\Configurator\FileTypeAdapter\IniAdapter();
        $this->assertInternalType('array', $adapter->loadFrom('foo'));
    }


    public function testParse()
    {
        global $_EXAMPLE_INI;
        $adapter = new \Slender\Configurator\FileTypeAdapter\IniAdapter();

        // Create tmp file to load from
        $tmpFile = sys_get_temp_dir().'/tmp-configurator.ini';
        file_put_contents($tmpFile,$_EXAMPLE_INI);


        $result = $adapter->parse($tmpFile);


        $this->assertCount(2,$result);

        $this->assertArrayHasKey('foo',$result);
        $this->assertEquals('bar', $result['foo']);

        $this->assertArrayHasKey('subfoo', $result);
        $this->assertCount(1, $result['subfoo']);
        $this->assertArrayHasKey('baz', $result['subfoo']);
        $this->assertEquals('fwibble', $result['subfoo']['baz']);
    }
}
