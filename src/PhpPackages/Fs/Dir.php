<?php namespace PhpPackages\Fs;

use RecursiveDirectoryIterator,
    FilesystemIterator,
    SplFileInfo,
    RecursiveIteratorIterator; // Cool naming.

class Dir extends Path {

    /**
     * @var RecursiveIteratorIterator|null
     */
    protected $iterator = null;

    /**
     * {@inheritdoc}
     */
    public function __construct($path)
    {
        parent::__construct($path);

        if ($this->isDir()) {
            $this->iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
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
     * @param string $path
     * @return bool
     */
    public function contains($path)
    {
        if (is_null($this->iterator)) {
            return false;
        }

        return (new Path($this->path))->join($path)->isReadable();
    }

    /**
     * @param string $path
     * @return Path|null
     */
    public function item($path)
    {
        if ( ! $this->contains($path)) {
            return null;
        }

        return (new Path($this->path))->join($path);
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

            // ...
        }

        return rmdir($this->path);
    }
}
