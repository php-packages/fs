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
}
