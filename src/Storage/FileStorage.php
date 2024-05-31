<?php

namespace SimpleRateLimiter\Storage;

class FileStorage implements StorageInterface
{
    private string $_directory;

    public function __construct(string $directory)
    {
        $this->_directory = $directory;
        if (!is_dir($this->_directory)) {
            mkdir($this->_directory,0777, true);
        }
    }

    private function _getFilePath($key) : string
    {
        return $this->_directory . DIRECTORY_SEPARATOR . md5($key) . ".json";
    }

    public function get($key)
    {
        $filePath = $this->_getFilePath($key);
        if (!file_exists($filePath)) {
            return null;
        }
        return json_decode(file_get_contents($filePath), true);
    }

    public function set($key, $value, $ttl = null) :bool
    {
        $filePath = $this->_getFilePath($key);
        if (!file_exists($filePath)) {
            touch($filePath);
        }

        $file = fopen($filePath, "r+");
        if (!$file) {
            fclose($file);
            return false;
        }
        if (!flock($file, LOCK_EX)) {
            fclose($file);
            return false;
        }

        file_put_contents($filePath, json_encode($value));
        flock($file, LOCK_UN);
        fclose($file);
        return true;

    }

    public function delete($key) : bool
    {
        $filePath = $this->_getFilePath($key);
        if (file_exists($filePath)) {
            return unlink($filePath);
        } else {
            return false;
        }
    }
}