<?php

require_once(__DIR__ . '/../KvsTest.php');

class Kvs_FilesystemTest extends KvsTest
{
    public function setUp()
    {
        $this->store = new Kvs_Filesystem("test");
    }
}
