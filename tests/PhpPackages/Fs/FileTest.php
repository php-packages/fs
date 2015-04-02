<?php namespace PhpPackages\Fs;

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
}
