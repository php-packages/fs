<?php namespace PhpPackages\Fs;

use org\bovigo\vfs\vfsStream as VFS;

class FileTest extends \TestCase {

    /**
     * @test
     */
    public function it_tells_whether_a_file_is_empty()
    {
        expect((new File(uniqid()))->isEmpty())->to_be(true);
        expect((new File(__FILE__))->isEmpty())->to_be(false);
    }

    /**
     * @test
     */
    public function it_reads_a_file()
    {
        expect((new File(uniqid()))->read())->to_be(null);
        expect((new File(__FILE__))->read())->to_be_a('string');
    }

    /**
     * @test
     */
    public function it_loads_a_PHP_file()
    {
        expect((new File(uniqid()))->load())->to_be(false);
        expect((new File(FS_FIXTURES . ds() . 'data.php'))->load())->to_be_an('array');
    }

    /**
     * @test
     */
    public function it_splits_file_contents_into_lines()
    {
        expect((new File(uniqid()))->lines())->to_be([]);
        expect((new File(__FILE__))->lines())->not_to_have_length(0);
    }

    /**
     * @test
     */
    public function it_returns_file_size()
    {
        expect((new File(uniqid()))->size())->to_be(0);
        expect((new File(__FILE__))->size())->to_be_above(0);
    }

    /**
     * @test
     */
    public function it_tells_whether_given_file_contains_some_string()
    {
        expect((new File(uniqid()))->contains('foo'))->to_be(false);
        expect((new File(__FILE__))->contains(uniqid()))->to_be(false);

        expect((new File(__FILE__))->contains('contains'))->to_be(true);
    }

    /**
     * @test
     */
    public function it_returns_last_modification_time()
    {
        expect((new File(uniqid()))->lastModified())->to_be(null);
        expect((new File(__FILE__))->lastModified())->to_be_an('integer');
        expect((new File(__FILE__))->lastModified('H:i:s Y-m-d'))->to_be_a('string');
    }

    /**
     * @test
     */
    public function it_tells_whether_file_contents_matches_given_regular_expression()
    {
        expect((new File(uniqid()))->matches('/^(.+)$/'))->to_be(false);
        expect((new File(__FILE__))->matches('/class\s(\w+)/'))->to_be(true);
    }

    /**
     * @test
     */
    public function it_performs_searching_in_file_contents()
    {
        expect((new File(uniqid()))->search('/^(.+)$/'))->to_be([]);
        expect((new File(__FILE__))->search('/^(.+)$/'))->not_to_have_length(0);
    }

    /**
     * @test
     */
    public function it_rewrites_file_contents()
    {
        $file = new File($this->setUpVfs() . ds() . 'example');

        expect($file->rewrite('foo'))->to_be(true);
        expect($file->read())->to_be('foo');

        expect($file->rewrite('bar'))->to_be(true);
        expect($file->read())->to_be('bar');

        expect($file->truncate())->to_be(true);
        expect($file->read())->to_be('');
    }

    /**
     * @test
     */
    public function it_appends_and_prepends_contents()
    {
        $file = new File($this->setUpVfs() . ds() . 'example');

        expect($file->rewrite('foo'))->to_be(true);
        expect($file->read())->to_be('foo');

        expect($file->append('bar'))->to_be(true);
        expect($file->read())->to_be('foobar');

        expect($file->prepend('baz'))->to_be(true);
        expect($file->read())->to_be('bazfoobar');
    }

    /**
     * @test
     */
    public function it_removes_a_file()
    {
        expect((new File(uniqid()))->remove())->to_be(false);

        $file = new File($this->setUpVfs() . ds() . 'example');

        expect($file->rewrite('foo'))->to_be(true);
        expect($file->read())->to_be('foo');
        expect($file->remove())->to_be(true);
        expect($file->read())->to_be(null);
    }

    /**
     * @return string
     */
    protected function setUpVfs()
    {
        VFS::setup('test');

        return VFS::url('test');
    }
}
