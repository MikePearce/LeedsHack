<?php

require_once(__DIR__ . '/../KvsTest.php');

class Kvs_TokyoTyrantTest extends KvsTest
{
    public function setUp()
    {
        $this->store = new Kvs_TokyoTyrant(new TokyoTyrant('localhost'), 'test');
    }
}
