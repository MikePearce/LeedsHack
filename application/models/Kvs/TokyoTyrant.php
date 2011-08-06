<?php

class Kvs_TokyoTyrant extends Kvs
{
    protected $server;
    protected $db;

    public function __construct(TokyoTyrant $server, $db)
    {
        $this->server = $server;
        $this->db = $db;
    }

    public function exists($id)
    {
        return ($this->server->get($this->getKey($id)) !== null);
    }

    public function save($id, $value)
    {
        $this->server->put($this->getKey($id), $value);
    }

    public function load($id)
    {
        $key = $this->getKey($id);
        $value = $this->server->get($key);
        if (is_null($value)) {
            throw new InvalidArgumentException("Key '$key' not found in database");
        }
        return $value;
    }

    public function delete($id)
    {
        $this->server->out($this->getKey($id));
    }

    protected function getKey($id)
    {
        return $this->db . '/' . $id;
    }
}
