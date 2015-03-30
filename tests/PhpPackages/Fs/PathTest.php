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
    public function it_checks_if_it_is_a_file()
    {
        expect((new Path(uniqid()))->isFile())->to_be(false);
        expect((new Path(__FILE__))->isFile())->to_be(true);
    }

    /**
     * @test
     */
    public function it_returns_Dir_instance()
    {
        expect($file = (new Path('foo'))->asDir())->to_be_a('PhpPackages\\Fs\\Dir');
        expect($file->path())->to_be('foo');
    }

    /**
     * @test
     */
    public function it_joins_two_paths()
    {
        expect((new Path('foo'))->join('bar')->path())->to_be('foo' . ds() . 'bar');
        expect((new Path('foo' . ds()))->join('bar')->path())->to_be('foo' . ds() . 'bar');
    }

    /**
     * @test
     */
    public function it_returns_full_path()
    {
        expect((new Path(ds() . 'foo'))->full('bar')->path())->to_be(ds() . 'foo');
        expect((new Path('foo'))->full()->path())->to_be(getcwd() . ds() . 'foo');
        expect((new Path('foo'))->full('bar' . ds())->path())->to_be('bar' . ds() . 'foo');
    }
}
