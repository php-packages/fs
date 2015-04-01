<?php namespace PhpPackages\Fs;

class FileTest extends \TestCase {

    /**
     * @test
     */
    public function it_tells_whether_file_is_empty()
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
}
