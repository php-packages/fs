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

        expect($path = $dir->item(basename(__FILE__)))->to_be_a('PhpPackages\\Fs\\Path');
        expect($path->path())->to_be(__FILE__);

        // ====================================
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
        expect((new Dir(__DIR__))->files())->to_contain(basename(__FILE__));
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
        expect((new Dir($this->getPath()))->remove())->to_be(false);
    }

    /**
     * @return string
     */
    protected function getPath()
    {
        $dir = VFS::setup('fs-test');

        VFS::copyFromFileSystem(__DIR__, $dir);

        return VFS::url('fs-test');
    }
}
