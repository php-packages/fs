<?php namespace PhpPackages\Fs;

use RecursiveDirectoryIterator,
    RecursiveIteratorIterator, // Cool naming.
    FilesystemIterator,
    SplFileInfo,
    IteratorAggregate,
    ArrayIterator;

class Dir extends Path implements IteratorAggregate {

    /**
     * @var RecursiveIteratorIterator|null
     */
    protected $iterator = null;

    /**
     * @param string $path
     * @return Dir
     */
    public function __construct($path)
    {
        parent::__construct($path);

        if ($this->isDir()) {
            $this->createIterator();
        }
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return (is_null($this->iterator) or count($this->iterator) < 1);
    }

    /**
     * @return bool
     */
    public function make()
    {
        if ( ! $this->isDir()) {
            $result = mkdir($this->path);

            $this->reload();

            return $result;
        }

        return false;
    }

    /**
     * @param string|Path $path
     * @return bool
     */
    public function contains($path)
    {
        if (is_null($this->iterator)) {
            return false;
        }

        return $this->replica()->join($path)->isReadable();
    }

    /**
     * @param string|Path $path
     * @return Path|null
     */
    public function item($path)
    {
        if ( ! $this->contains($path)) {
            return null;
        }

        return $this->replica()->join($path);
    }

    /**
     * @param callable|null $filter
     * @return array
     */
    public function all(callable $filter = null)
    {
        $items = [];

        if (is_null($this->iterator)) {
            return $items;
        }

        foreach ($this->iterator as $item) {
            if (is_null($filter) or $filter($item)) {
                $items[] = $item->getFilename();
            }
        }

        return $items;
    }

    /**
     * @return array
     */
    public function files()
    {
        return $this->all(function(SplFileInfo $item) {
            return $item->isFile();
        });
    }

    /**
     * @return array
     */
    public function dirs()
    {
        return $this->all(function(SplFileInfo $item) {
            return $item->isDir();
        });
    }

    /**
     * @param bool $force
     * @return bool
     */
    public function remove($force = false)
    {
        if (is_null($this->iterator)) {
            return false;
        }

        if ( ! $this->isEmpty()) {
            // We need to remove all stored files/directories recursively.
            if ( ! $force) {
                // Prevent someone from making a bad decision.
                return false;
            }

            $this->doRemove();
        }

        return rmdir($this->path);
    }

    /**
     * @param string|Path $item
     * @return void
     */
    protected function doRemove($item = '')
    {
        $item = path($item)->full($this->path);

        if ($item->isFile()) {
            return unlink($item->path());
        }

        foreach ($item->asDir()->all() as $subItem) {
            $this->doRemove($subItem);
        }
    }

    /**
     * @param string|Path $path
     * @return bool
     */
    public function copyTo($path)
    {
        $result = $this->doCopy($path);

        $this->reload();

        return $result;
    }

    /**
     * @param string|Path $path
     * @return bool
     */
    public function copyFrom($path)
    {
        $result = path($path)->asDir()->copyTo($this->path);

        $this->reload();

        return $result;
    }

    /**
     * @param string|Path $to
     * @param string|null $from
     * @return bool
     */
    protected function doCopy($to, $from = null)
    {
        $success = true;
        $from = $from ?: $this->path;

        foreach (path($from)->asDir()->all() as $itemPath) {
            $item = path($itemPath)->full($from);

            if ($item->isFile()) {
                // Attempt to copy.
                $success = copy($item->path(), path($to)->join($itemPath)->path());

                continue;
            }

            $success = $this->doCopy($to, path($itemPath)->full($from)->path());
        }

        return $success;
    }

    /**
     * @param string|Path $path
     * @return bool
     */
    public function moveTo($path)
    {
        path($path)->asDir()->make();

        return ($this->copyTo($path) and $this->remove(true));
    }

    /**
     * @return object
     */
    public function reload()
    {
        $this->createIterator();

        return $this;
    }

    /**
     * @return RecursiveIteratorIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->files());
    }

    /**
     * @return void
     */
    protected function createIterator()
    {
        $this->iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->path, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
    }
}
