<?php

class WalletTest extends PHPUnit_Framework_TestCase
{
    public function testSaveAndLoad()
    {
        $kvs = Kvs::get('test');
        $codec = WalletCodec::get();

        $data = array("foo" => 1, "bar" => 2);
        $wallet = new Wallet($kvs, WalletCodec::get(), "foo", $data);
        $wallet->save("secret");

        $wallet2 = Wallet::load($kvs, $codec, "foo", "secret");
        $this->assertEquals($wallet, $wallet2);

        $kvs->delete('foo');
    }
}
