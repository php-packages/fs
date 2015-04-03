<?php namespace PhpPackages\Fs;

class File extends Path {

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return ( ! $this->isReadable() or $this->read() === '');
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
     * @param string $contents
     * @return bool
     */
    public function rewrite($contents)
    {
        return (boolean) file_put_contents($this->path, $contents);
    }

    /**
     * @return bool
     */
    public function truncate()
    {
        $this->rewrite('');

        return $this->read() === '';
    }

    /**
     * @param string $contents
     * @return bool
     */
    public function append($contents)
    {
        return $this->rewrite($this->read() . $contents);
    }

    /**
     * @param string $contents
     * @return bool
     */
    public function prepend($contents)
    {
        return $this->rewrite($contents . $this->read());
    }

    /**
     * @return bool
     */
    public function remove()
    {
        if ( ! $this->isFile()) {
            return false;
        }

        return unlink($this->path);
    }

    /**
     * @param string|Path $to
     * @return bool
     */
    public function copyTo($to)
    {
        if ( ! $this->isFile()) {
            return false;
        }

        return copy($this->path, (string) $to);
    }

    /**
     * @param string|Path $to
     * @return bool
     */
    public function moveTo($to)
    {
        if ( ! $this->isFile()) {
            return false;
        }

        return (path($to)->asFile()->rewrite($this->read()) and $this->remove());
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
     * @param callable|null $callback
     * @return int|mixed
     */
    public function size(callable $callback = null)
    {
        if ( ! $this->isFile()) {
            return 0;
        }

        return is_null($callback) ? filesize($this->path) : $callback(filesize($this->path));
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
     * @param string $regex
     * @return bool
     */
    public function matches($regex)
    {
        return ($this->isFile() and (boolean) preg_match($regex, $this->read()));
    }

    /**
     * @param string $regex
     * @return array
     */
    public function search($regex)
    {
        $matches = [];

        if ( ! $this->isFile()) {
            return $matches;
        }

        preg_match_all($regex, $this->read(), $matches);

        return $matches;
    }
}
