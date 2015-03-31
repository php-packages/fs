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

        return is_readable((new Path($this->path))->join($path)->path());
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
}
