<?php

namespace Storage;

interface StorageInterface
{
    public function get( $key);

    public function set( $key,  $value, $ttl = null);

    public function delete( $key);
}