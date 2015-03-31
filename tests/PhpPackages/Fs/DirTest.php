<?php namespace PhpPackages\Fs;

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
}
