<?php namespace PhpPackages\Fs;

use org\bovigo\vfs\vfsStream as VFS;

class FileTest extends \TestCase {

    /**
     * @test
     */
    public function it_tells_whether_a_file_is_empty()
    {
        expect($this->makeFake()->isEmpty())->to_be(true);
        expect($this->makeReal()->isEmpty())->to_be(false);
    }

    /**
     * @test
     */
    public function it_reads_a_file()
    {
        expect($this->makeFake()->read())->to_be(null);
        expect($this->makeReal()->read())->to_be_a('string');
    }

    /**
     * @test
     */
    public function it_loads_a_PHP_file()
    {
        expect($this->makeFake()->load())->to_be(false);

        $file = path(ds($this->setUpVfs(), 'example'))->asFile();

        expect($file->rewrite('<?php return 123;'))->to_be(true);
        expect($file->load())->to_be(123);
    }

    /**
     * @test
     */
    public function it_splits_file_contents_into_lines()
    {
        expect($this->makeFake()->lines())->to_be([]);
        expect($this->makeReal()->lines())->not_to_have_length(0);
    }

    /**
     * @test
     */
    public function it_returns_the_file_size()
    {
        expect($this->makeFake()->size())->to_be(0);
        expect($this->makeReal()->size())->to_be_above(0);

        expect($this->makeReal()->size(function($size) {
            return is_int($size);
        }))->to_be(true);
    }

    /**
     * @test
     */
    public function it_tells_whether_given_file_contains_some_string()
    {
        expect($this->makeFake()->contains('foo'))->to_be(false);
        expect($this->makeReal()->contains(uniqid()))->to_be(false);

        expect($this->makeReal(__FILE__)->contains('contains'))->to_be(true);
    }

    /**
     * @test
     */
    public function it_returns_last_modification_time()
    {
        expect($this->makeFake()->lastModified())->to_be(null);
        expect($this->makeReal()->lastModified())->to_be_an('integer');
        expect($this->makeReal()->lastModified('H:i:s Y-m-d'))->to_be_a('string');
    }

    /**
     * @test
     */
    public function it_tells_whether_file_contents_matches_given_regular_expression()
    {
        expect($this->makeFake()->matches('/^(.+)$/'))->to_be(false);
        expect($this->makeReal()->matches('/class\s(\w+)/'))->to_be(true);
    }

    /**
     * @test
     */
    public function it_performs_searching_in_file_contents()
    {
        expect($this->makeFake()->search('/^(.+)$/'))->to_be([]);
        expect($this->makeReal()->search('/^(.+)$/'))->not_to_have_length(0);
    }

    /**
     * @test
     */
    public function it_rewrites_file_contents()
    {
        $file = path(ds($this->setUpVfs(), 'example'))->asFile();

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
        $file = path(ds($this->setUpVfs(), 'example'))->asFile();

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
        expect($this->makeFake()->remove())->to_be(false);

        $file = path(ds($this->setUpVfs(), 'example'))->asFile();

        expect($file->rewrite('foo'))->to_be(true);
        expect($file->read())->to_be('foo');
        expect($file->remove())->to_be(true);
        expect($file->read())->to_be(null);
    }

    /**
     * @test
     */
    public function it_copies_and_moves_files()
    {
        $dir = $this->setUpVfs() . ds();
        $file = path($dir . 'foo')->asFile();

        expect($file->copyTo($dir . 'bar'))->to_be(false);
        expect($file->rewrite('123'))->to_be(true);
        expect($file->copyTo($dir . 'bar'))->to_be(true);

        $anotherFile = path($dir . 'baz')->asFile();

        expect($anotherFile->read())->to_be(null);
        expect($file->moveTo($dir . 'baz'))->to_be(true);
        expect($anotherFile->read())->to_be('123');

        // Can't move, the original file was removed.
        expect($file->moveTo($dir . 'baz'))->to_be(false);
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
