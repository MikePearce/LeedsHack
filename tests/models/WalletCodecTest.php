<?php

class WalletCodecTest extends PHPUnit_Framework_TestCase
{
    protected $codec;

    public function setUp()
    {
        $this->codec = new WalletCodec;
    }

    public function testCanDecode()
    {
        $data = array("foo" => "bar", "baz" => "qux");
        $blob = $this->codec->encode($data, "secret");
        $this->assertEquals($data, $this->codec->decode($blob, "secret"));
    }
}
