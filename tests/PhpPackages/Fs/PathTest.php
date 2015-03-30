<?php namespace PhpPackages\Fs;

class PathTest extends \TestCase {

    /**
     * @test
     */
    public function it_returns_passed_path_value()
    {
        expect((new Path('foo'))->path())->to_be('foo');
    }

    /**
     * @test
     */
    public function it_returns_File_instance()
    {
        expect($file = (new Path('foo'))->asFile())->to_be_a('PhpPackages\\Fs\\File');
        expect($file->path())->to_be('foo');
    }

    /**
     * @test
     */
    public function it_returns_Dir_instance()
    {
        expect($file = (new Path('foo'))->asDir())->to_be_a('PhpPackages\\Fs\\Dir');
        expect($file->path())->to_be('foo');
    }
}
