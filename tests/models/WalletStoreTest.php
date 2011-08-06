<?php

class WalletStoreTest extends PHPUnit_Framework_TestCase
{
    public function testStuff()
    {
        $s = new WalletStore();
        $s->save("test", "foobar");
        $this->assertEquals("foobar", $s->load("test"));
        $s->delete("test");
    }
}
