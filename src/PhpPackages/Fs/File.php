<?php namespace PhpPackages\Fs;

class File extends Path {

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return ( ! $this->isReadable() or filesize($this->path) < 1);
    }
}
