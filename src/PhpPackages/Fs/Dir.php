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
     * @param string $path
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
     * @param string $path
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
     * @param string $item
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
     * @param string $path
     * @return bool
     */
    public function copyTo($path)
    {
        $result = $this->doCopy($path);

        $this->createIterator();

        return $result;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function copyFrom($path)
    {
        return path($path)->asDir()->copyTo($this->path);
    }

    /**
     * @param string $to
     * @return bool
     */
    protected function doCopy($to)
    {
        $success = true;

        foreach (path($this->path)->asDir()->all() as $itemPath) {
            $item = path($itemPath)->full($this->path);

            if ($item->isFile()) {
                // Attempt to copy.
                $success = copy($item->path(), path($to)->join($itemPath)->path());

                continue;
            }

            $success = $this->doCopy($item->asDir());
        }

        return $success;
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
