<?php namespace PhpPackages\Fs;

use RecursiveDirectoryIterator,
    FilesystemIterator,
    RecursiveIteratorIterator; // Cool naming.

class Dir extends Path {

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
}
