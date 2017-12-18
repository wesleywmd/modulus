<?php
use Modulus\Components\Config\ConfigArray;
use PHPUnit\Framework\TestCase;

class ConfigArrayTest extends TestCase
{
    /**
     * @test
     */
    public function testAddItem()
    {
        $testArray = new ConfigArray();
        $this->assertFalse($testArray->exists("a"), "Test if item [a] doesn't exist.");
        $testArray->set("a",10);
        $this->assertTrue($testArray->exists("a"), "Test if item [a] does exist.");
        $this->assertEquals(10, $testArray->get("a"), "Test if item [a] equals 10.");
        $this->assertEquals(["a"=>10], $testArray->get(), "Test if item [] equals array.");
        $testArray->remove("a");
        $this->assertFalse($testArray->exists("a"), "Test if item [a] doesn't exist after remove.");
    }

    /**
     * @test
     */
    public function testNestedAddItem()
    {
        $testArray = new ConfigArray(["a"=>["b"=>10]]);
        $testArray->set("a:b",10);
        $this->assertTrue($testArray->exists("a:b"), "Test if item [a:b] does exist.");
        $this->assertEquals(10, $testArray->get("a:b"), "Test if item [a:b] equals 10.");
        $this->assertEquals(["a"=>["b"=>10]], $testArray->get(), "Test if item [] equals array.");
        $testArray->remove("a:b");
        $this->assertFalse($testArray->exists("a:b"), "Test if item [a:b] doesn't exist.");
        $this->assertTrue($testArray->exists("a"), "Test if item [a] does exist.");
    }

    /**
     * @test
     */
    public function testGroupItems()
    {
        $testArray = new ConfigArray();
        $this->assertFalse($testArray->exists("a:b:c:d"),"Test if item [a:b:c:d] doesn't exist.");
        $testArray->set("a:b:c:d",10);
        $this->assertTrue($testArray->exists("a:b:c:d"), "Test if item [a:b:c] does exist.");
        $this->assertEquals(["d"=>10], $testArray->get("a:b:c"), "Test if item [a:b:c] equals array[].");
        $this->assertEquals(["c"=>["d"=>10]], $testArray->get("a:b"), "Test if item [a:b] equals array[].");
        $this->assertEquals(["b"=>["c"=>["d"=>10]]],$testArray->get("a"),"Test if item [a] equals array[].");
        $this->assertEquals(["a"=>["b"=>["c"=>["d"=>10]]]], $testArray->get(), "Test if item [] equals array.");
        $testArray->remove("a:b:c");
        $this->assertTrue($testArray->exists("a"), "Test if item [a] does exist.");
        $this->assertTrue($testArray->exists("a:b"), "Test if item [a:b] does exist.");
        $this->assertFalse($testArray->exists("a:b:c"), "Test if item [a:b:c] doesn't exist.");
        $this->assertFalse($testArray->exists("a:b:c:d"), "Test if item [a:b:c:d] doesn't exist.");
    }

    /**
     * @test
     */
    public function testComplexRemove()
    {
        $testArray = new ConfigArray();
        $testArray->set("a:b:c",10);
        $testArray->set("d:e:f",10);
        $testArray->remove("a:b");
        $this->assertEquals(["d"=>["e"=>["f"=>10]],"a"=>[]], $testArray->get(), "Test if item [] equals array.");
        $this->assertEquals(["e"=>["f"=>10]], $testArray->get("d"), "Test if item [d] equals array.");
        $this->assertEquals(["f"=>10], $testArray->get("d:e"), "Test if item [d:e] equals array.");
        $this->assertEquals(10, $testArray->get("d:e:f"), "Test if item [d:e:f] equals array.");


    }
}