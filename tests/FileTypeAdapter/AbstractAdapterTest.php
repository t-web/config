<?php

class AbstractAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testLoadFrom()
    {
        $stub = $this->getMockBuilder('\Slender\Configurator\FileTypeAdapter\AbstractAdapter')
            ->setConstructorArgs(['recursive' => true])
            ->getMockForAbstractClass();

        $stub->expects($this->any())
            ->method('loadFrom')
            ->will($this->returnValue([]));

        $this->assertEquals([], $stub->loadFrom('./config'));
    }
}
