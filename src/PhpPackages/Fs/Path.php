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
        return (string) $this->path;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->path;
    }

    /**
     * @return bool
     */
    public function isAbsolute()
    {
        return $this->path[0] == ds();
    }

    /**
     * @return bool
     */
    public function isRelative()
    {
        return ! $this->isAbsolute();
    }

    /**
     * @return array
     */
    public function parts()
    {
        return explode(ds(), $this->path);
    }

    /**
     * @return string
     */
    public function short()
    {
        return basename($this->path);
    }

    /**
     * @return object
     */
    public function shorten()
    {
        $this->path = basename($this->path);

        return $this;
    }

    /**
     * @return string
     */
    public function skipName()
    {
        $nameParts = explode(ds(), $this->path);

        if (count($nameParts) > 1) {
            array_pop($nameParts);
        }

        return implode(ds(), $nameParts);
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
     * @return bool
     */
    public function isReadable()
    {
        return is_readable($this->path);
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

    /**
     * @return object
     */
    public function replica()
    {
        return new static($this->path);
    }
}
