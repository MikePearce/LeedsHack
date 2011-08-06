<?php

class WalletTest extends PHPUnit_Framework_TestCase
{
    protected $kvs;
    protected $codec;

    public function setUp()
    {
        $this->kvs = Kvs::get('test');
        $this->codec = WalletCodec::get();
    }

    public function testSaveAndLoad()
    {
        $data = array("foo" => 1, "bar" => 2);
        $wallet = new Wallet($this->kvs, WalletCodec::get(), "foo", $data);
        $wallet->save("secret");

        $wallet2 = Wallet::load($this->kvs, $this->codec, "foo", "secret");
        $this->assertEquals($wallet, $wallet2);

        $this->kvs->delete('foo');
    }

    /**
     * @expectedException WalletNotFound
     */
    public function testLoadNotFound()
    {
        Wallet::load($this->kvs, $this->codec, "foo", "secret");
    }
}
