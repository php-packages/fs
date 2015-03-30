<?php namespace PhpPackages\Fs;

class PathTest extends \TestCase {

    /**
     * @test
     */
    public function it_returns_passed_path_value()
    {
        expect((new Path('foo'))->path())->to_be('foo');
    }
}
