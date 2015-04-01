<?php namespace PhpPackages\Fs;

use org\bovigo\vfs\vfsStream as VFS;

class DirTest extends \TestCase {

    /**
     * @test
     */
    public function it_tells_if_directory_contains_no_items()
    {
        expect((new Dir(uniqid()))->isEmpty())->to_be(true);
        expect((new Dir(__DIR__))->isEmpty())->to_be(false);
    }

    /**
     * @test
     */
    public function it_checks_if_directory_contains_an_item()
    {
        expect((new Dir(uniqid()))->contains(uniqid()))->to_be(false);
        expect((new Dir(__DIR__))->contains(basename(__FILE__)))->to_be(true);
    }

    /**
     * @test
     */
    public function it_returns_items_stored_in_the_directory()
    {
        $dir = new Dir(__DIR__);

        expect($dir->item(uniqid()))->to_be(null);

        $path = $dir->item(basename(__FILE__));
        expect($path->path())->to_be(__FILE__);

        expect((new Dir(uniqid()))->item(uniqid()))->to_be(null);
    }

    /**
     * @test
     */
    public function it_returns_all_items()
    {
        expect((new Dir(uniqid()))->all())->to_be([]);
        expect((new Dir(__DIR__))->all())->not_to_be([]);
    }

    /**
     * @test
     */
    public function it_returns_all_files()
    {
        $dir = new Dir(__DIR__);

        expect($dir->files())->not_to_have_length(0);
        expect($dir->files())->to_include(basename(__FILE__));
    }

    /**
     * @test
     */
    public function it_returns_all_dirs()
    {
        expect((new Dir(__DIR__))->dirs())->not_to_contain(basename(__FILE__));
    }

    /**
     * @test
     */
    public function it_removes_a_directory()
    {
        expect((new Dir(uniqid()))->remove())->to_be(false);
        expect((new Dir($path = $this->getPath()))->remove())->to_be(false);

        expect((new Dir($path))->remove(true))->to_be(true);
        expect((new Path($path))->isReadable())->to_be(false);
    }

    /**
     * @test
     */
    public function it_copies_a_directory()
    {
        $dir = new Dir($this->getPath(false));

        expect($dir->all())->to_have_length(0);
        expect($dir->copyFrom(__DIR__ . ds() . '..'))->to_be(true);
        expect($dir->all())->not_to_have_length(0);
    }

    /**
     * @test
     */
    public function it_moves_a_directory()
    {
        $dir = new Dir('/tmp/' . uniqid());

        expect($dir->make())->to_be(true);
        expect($dir->copyFrom(__DIR__))->to_be(true);

        // Prepare another directory.
        $anotherDir = new Dir($this->getPath(false));

        // Test.
        expect($anotherDir->all())->to_have_length(0);
        expect($dir->all())->not_to_have_length(0);
        expect($dir->moveTo($anotherDir->path()))->to_be(true);
        expect($anotherDir->reload()->all())->not_to_have_length(0);
    }

    /**
     * @test
     */
    public function it_makes_a_directory()
    {
        $dir = new Dir($this->getPath(false));

        expect($dir->isReadable())->to_be(true);
        expect($dir->remove(true))->to_be(true);
        expect($dir->isReadable())->to_be(false);
        expect($dir->make())->to_be(true);
        expect($dir->isReadable())->to_be(true);

        expect($dir->make())->to_be(false);
    }

    /**
     * @param bool $copy
     * @return string
     */
    protected function getPath($copy = true)
    {
        $dir = VFS::setup('fs-test');

        if ($copy) {
            VFS::copyFromFileSystem(__DIR__, $dir);
        }

        return VFS::url('fs-test');
    }
}
