<?php namespace PhpPackages\Fs;

class Path {

    /**
     * @var string
     */
    protected $path;

    /**
     * @param string $path
     * @return Path
     */
    public function __construct($path)
    {
        $this->path = (string) $path;
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * @return File
     */
    public function asFile()
    {
        return new File($this->path);
    }

    /**
     * @return Dir
     */
    public function asDir()
    {
        return new Dir($this->path);
    }
}
