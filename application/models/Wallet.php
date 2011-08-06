<?php

class Wallet implements ArrayAccess
{
    static public function create($id)
    {
        return new self(Kvs::get('wallet'), WalletCodec::get(), $id);
    }

    static public function open($id, $passphrase)
    {
        return self::load(Kvs::get('wallet'), WalletCodec::get(), $id, $passphrase);
    }

    static public function load(Kvs $store, WalletCodec $codec, $id, $passphrase)
    {
        return new self($store, $codec, $id, $codec->decode($store->load($id), $passphrase));
    }

    protected $store;
    protected $codec;
    protected $id;
    protected $data;

    public function __construct(Kvs $store, WalletCodec $codec, $id, array $data = array())
    {
        $this->store = $store;
        $this->codec = $codec;
        $this->id = $id;
        $this->data = $data;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function save($passphrase)
    {
        $this->store->save($this->id, $this->codec->encode($this->data, $passphrase));
    }
}