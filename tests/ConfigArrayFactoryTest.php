<?php
use Modulus\Components\Config\ConfigArray;
use Modulus\Components\Config\ConfigArrayFactory;
use PHPUnit\Framework\TestCase;

class ConfigArrayFactoryTest extends TestCase
{
    public function testSimpleCreate()
    {
        $factory = new ConfigArrayFactory();
        $simple = $factory->create();
        $this->assertInstanceOf(ConfigArray::class,$simple,"Create a simple configArray.");
    }

    public function testPreloadedCreate()
    {
        $factory = new ConfigArrayFactory();
        $simple = $factory->create(["a"=>["b"=>10]]);
        $this->assertInstanceOf(ConfigArray::class,$simple,"Create a preloaded configArray.");
        $this->assertEquals(10, $simple->get("a:b"), "Check that the configArray gets preloaded from the factory.");
    }
}
