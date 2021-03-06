<?php

class Wallet implements ArrayAccess, IteratorAggregate
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
        try {
            $blob = $store->load($id);
        } catch(Exception $e) {
            throw new WalletNotFound("Failed to open wallet with ID '{$id}'", 0, $e);
        }

        $data = $codec->decode($blob, $passphrase);
        if (is_null($data)) {
            throw new BadWalletPassword("Bad password for wallet ID '{$id}'");
        }

        return new self($store, $codec, $id, $data);
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

    public function rename($passphrase, $newId)
    {
        $oldId = $this->id;
        $this->id = $newId;
        $this->save($passphrase);
        $this->store->delete($oldId);
    }
 
    public function getIterator() {
        return new ArrayIterator($this->data);
    }
    
    public function isEnabled()
    {
        if (isset($this->data['_disabled']) && $this->data['_disabled']) {
            return false;
        }
        return true;
    }
    
    public function disable($password)
    {
        $this->data['_disabled'] = true;
        $this->save($password);
        ActivityStream::create($this->id, 'Disabled Account');
    }
    
    public function enable($password)
    {
        $this->data['_disabled'] = false;
        $this->save($password);
        ActivityStream::create($this->id, 'Re-enabled Account');
    }
    
}
