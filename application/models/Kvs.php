<?php

abstract class Kvs
{
    static public function get($db)
    {
        return new Kvs_Filesystem($db);
    }

    abstract public function exists($id);
    abstract public function save($id, $value);
    abstract public function load($id);
    abstract public function delete($id);
}
