<?php

class Kvs_FilesystemTest extends PHPUnit_Framework_TestCase
{
    protected $store;

    public function setUp()
    {
        $this->store = new Kvs_Filesystem("test");
    }

    public function testSaveAndLoad()
    {
        $this->store->save("test", "foobar");
        $this->assertEquals("foobar", $this->store->load("test"));
        $this->store->delete("test");
    }

    public function testExistsFalse()
    {
        $this->assertFalse($this->store->exists("nonexistent"));
    }

    public function testExistsTrue()
    {
        $this->store->save("test", "foobar");
        $this->assertTrue($this->store->exists("test"));
        $this->store->delete("test");
    }

}
