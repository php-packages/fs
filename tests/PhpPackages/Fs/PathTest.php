<?php namespace PhpPackages\Fs;

class PathTest extends \TestCase {

    /**
     * @test
     */
    public function it_returns_the_passed_path_value()
    {
        $path = path('foo');

        expect($path->path())->to_be('foo');
        expect((string) $path)->to_be('foo');
    }

    /**
     * @test
     */
    public function it_detects_absolute_and_relative_paths()
    {
        $path = path('foo');

        expect($path->isRelative())->to_be(true);
        expect($path->isAbsolute())->to_be(false);

        $path = path(ds() . 'foo');

        expect($path->isRelative())->to_be(false);
        expect($path->isAbsolute())->to_be(true);
    }

    /**
     * @test
     */
    public function it_returns_path_parts()
    {
        expect(path(ds('foo', 'bar', 'baz'))->parts())->to_be(['foo', 'bar', 'baz']);
    }

    /**
     * @test
     */
    public function it_returns_short_path()
    {
        expect(path('foo')->short())->to_be('foo');
        expect(path(ds('foo', 'bar'))->short())->to_be('bar');
    }

    /**
     * @test
     */
    public function it_shortens_the_path()
    {
        expect(path(ds('foo', 'bar'))->shorten()->path())->to_be('bar');
    }

    /**
     * @test
     */
    public function it_skips_the_name()
    {
        expect(path('foo')->skipName())->to_be('foo');
        expect(path(ds('foo', 'bar'))->skipName())->to_be('foo');
    }

    /**
     * @test
     */
    public function it_returns_a_File_instance()
    {
        $file = path('foo')->asFile();

        expect($file->asFile())->to_be_a('PhpPackages\\Fs\\File');
        expect($file->path())->to_be('foo');
    }

    /**
     * @test
     */
    public function it_detects_a_file()
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
    public function it_checks_if_item_is_readable()
    {
        expect((new Path(uniqid()))->isReadable())->to_be(false);
        expect((new Path(__DIR__))->isReadable())->to_be(true);
        expect((new Path(__FILE__))->isReadable())->to_be(true);
    }

    /**
     * @test
     */
    public function it_checks_if_item_is_a_directory()
    {
        expect((new Path(uniqid()))->isDir())->to_be(false);
        expect((new Path(__DIR__))->isDir())->to_be(true);
    }

    /**
     * @test
     */
    public function it_joins_two_path_strings()
    {
        expect(path('foo')->join('bar')->path())->to_be(ds('foo', 'bar'));
        expect(path('foo' . ds())->join('bar')->path())->to_be(ds('foo', 'bar'));
    }

    /**
     * @test
     */
    public function it_returns_a_full_path()
    {
        expect(path(ds() . 'foo')->full('bar')->path())->to_be(ds() . 'foo');
        expect(path('foo')->full()->path())->to_be(ds(getcwd(), 'foo'));
        expect(path('foo')->full('bar' . ds())->path())->to_be(ds('bar', 'foo'));
    }

    /**
     * @test
     */
    public function it_resolves_path()
    {
        expect(path(ds('..', 'fs', '.', '..', 'fs'))->full()->resolve()->path())
            ->to_be(getcwd());
    }

    /**
     * @test
     */
    public function it_clones_the_instance()
    {
        $path = path('foo');
        $replica = $path->replica();

        expect($path)->not_to_be($replica);
        expect($path->path())->to_be($replica->path());
    }
}
