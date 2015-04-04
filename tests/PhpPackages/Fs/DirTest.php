<?php namespace PhpPackages\Fs;

use org\bovigo\vfs\vfsStream as VFS;

class DirTest extends \TestCase {

    /**
     * @test
     */
    public function it_is_traversable()
    {
        $dir = $this->makeFake();

        expect($dir)->to_respond_to('getIterator');
        expect($dir->getIterator())->to_be_a('ArrayIterator');
    }

    /**
     * @test
     */
    public function it_tells_if_directory_contains_no_items()
    {
        expect($this->makeFake()->isEmpty())->to_be(true);
        expect($this->makeReal(__DIR__)->isEmpty())->to_be(false);
    }

    /**
     * @test
     */
    public function it_checks_if_directory_contains_an_item()
    {
        expect($this->makeFake()->contains(uniqid()))->to_be(false);
        expect($this->makeReal(__DIR__)->contains(basename(__FILE__)))->to_be(true);
    }

    /**
     * @test
     */
    public function it_returns_items_stored_in_the_directory()
    {
        $dir = path(__DIR__)->asDir();
        $path = $dir->item(basename(__FILE__));

        expect($dir->item(uniqid()))->to_be(null);
        expect($path->path())->to_be(__FILE__);

        expect($this->makeFake()->item('foo'))->to_be(null);
    }

    /**
     * @test
     */
    public function it_returns_all_items()
    {
        expect($this->makeFake()->all())->to_be([]);

        $dir = path(__DIR__)->asDir();

        expect($dir->all())->to_contain(basename(__FILE__));
        expect($dir->all(null, true))->to_contain(__FILE__);
    }

    /**
     * @test
     */
    public function it_returns_all_files()
    {
        $dir = path(__DIR__)->asDir();

        expect($dir->files())->to_include(basename(__FILE__));
        expect($dir->files(true))->to_include(__FILE__);
    }

    /**
     * @test
     */
    public function it_returns_all_dirs()
    {
        $dir = path(__DIR__)->asDir();

        expect($dir->dirs())->not_to_contain(basename(__FILE__));
        expect($dir->dirs(true))->not_to_contain(__FILE__);
    }

    /**
     * @test
     */
    public function it_removes_a_directory()
    {
        expect($this->makeFake()->remove())->to_be(false);
        expect(path($path = $this->getPath())->asDir()->remove())->to_be(false);

        expect(path($path)->asDir()->remove(true))->to_be(true);
        expect(path($path)->isReadable())->to_be(false);
    }

    /**
     * @test
     */
    public function it_copies_a_directory()
    {
        $dir = path($this->getPath(false))->asDir();

        expect($dir->all())->to_have_length(0);
        expect($dir->copyFrom(ds(__DIR__, '..')))->to_be(true);

        expect($dir->all())->not_to_have_length(0);
    }

    /**
     * @test
     */
    public function it_moves_a_directory()
    {
        $dir = path(ds('', 'tmp', uniqid()))->asDir();

        expect($dir->make())->to_be(true);
        expect($dir->copyFrom(__DIR__))->to_be(true);

        // Prepare another directory.
        $anotherDir = path($this->getPath(false))->asDir();

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
        $dir = path($this->getPath(false))->asDir();

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
        $dir = VFS::setup('test');

        if ($copy) {
            VFS::copyFromFileSystem(__DIR__, $dir);
        }

        return VFS::url('test');
    }
}
