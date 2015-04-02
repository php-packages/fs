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

    /**
     * @param string $something
     * @return bool
     */
    public function contains($something)
    {
        return ($this->isFile() and strpos($this->read(), $something) !== false);
    }

    /**
     * @param string|null $format
     * @return null|int|string
     */
    public function lastModified($format = null)
    {
        if ( ! $this->isFile()) {
            return null;
        }

        $time = filemtime($this->path);

        return is_null($format) ? $time : date($format, $time);
    }

    /**
     * @param string $something
     * @return bool
     */
    public function matches($something)
    {
        return ($this->isFile() and (boolean) preg_match($something, $this->read()));
    }
}
