<?php namespace PhpPackages\Fs;

class File extends Path {

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return ( ! $this->isReadable() or filesize($this->path) < 1);
    }

    /**
     * @return null|string
     */
    public function read()
    {
        if ( ! $this->isFile()) {
            return null;
        }

        return file_get_contents($this->path);
    }

    /**
     * @return bool|mixed
     */
    public function load()
    {
        if ( ! $this->isFile()) {
            return false;
        }

        return require $this->path;
    }

    /**
     * @return array
     */
    public function lines()
    {
        if ( ! $this->isFile()) {
            return [];
        }

        return file($this->path);
    }

    /**
     * @return int
     */
    public function size()
    {
        if ( ! $this->isFile()) {
            return 0;
        }

        return filesize($this->path);
    }
}
