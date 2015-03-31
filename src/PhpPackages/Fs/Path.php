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
     * @return string
     */
    public function shortPath()
    {
        $nameParts = explode(ds(), $this->path);

        return end($nameParts);
    }

    /**
     * @return File
     */
    public function asFile()
    {
        return new File($this->path);
    }

    /**
     * @return bool
     */
    public function isFile()
    {
        return (is_readable($this->path) and is_file($this->path));
    }

    /**
     * @return Dir
     */
    public function asDir()
    {
        return new Dir($this->path);
    }

    /**
     * @return bool
     */
    public function isDir()
    {
        return (is_readable($this->path) and is_dir($this->path));
    }

    /**
     * @param string $path
     * @return object
     */
    public function join($path)
    {
        $lastChar = substr($this->path, strlen($this->path) - 1);

        $this->path .= (ds() == $lastChar) ? $path : (ds() . $path);

        return $this;
    }

    /**
     * @param string|null $parentDir
     * @return object
     */
    public function full($parentDir = null)
    {
        $firstChar = substr($this->path, 0, 1);

        if (ds() != $firstChar) {
            $parentDir = $parentDir ?: getcwd();

            $this->path = (new static($parentDir))->join($this->path)->path();
        }

        return $this;
    }

    /**
     * @return object
     */
    public function resolve()
    {
        $this->path = realpath($this->path);

        return $this;
    }
}
