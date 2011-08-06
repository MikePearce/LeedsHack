<?php

class WalletStore
{
    static public function get()
    {
        return new self;
    }

    public function exists($id)
    {
        return file_exists($this->getPath($id));
    }

    public function save($id, $value)
    {
        file_put_contents($this->getPath($id), $value);
    }

    public function load($id)
    {
        return file_get_contents($this->getPath($id));
    }

    public function delete($id)
    {
        unlink($this->getPath($id));
    }

    protected function getPath($id)
    {
        return BASE_PATH . "/data/{$id}";
    }
}
