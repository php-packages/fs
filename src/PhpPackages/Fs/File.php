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
}
