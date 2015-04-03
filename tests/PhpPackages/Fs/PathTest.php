<?php namespace PhpPackages\Fs;

class PathTest extends \TestCase {

    /**
     * @test
     */
    public function it_returns_passed_path_value()
    {
        $path = new Path('foo');

        expect($path->path())->to_be('foo');
        expect((string) $path)->to_be('foo');
    }

    /**
     * @test
     */
    public function it_returns_short_path()
    {
        expect((new Path('foo'))->short())->to_be('foo');
        expect((new Path(ds('foo', 'bar')))->short())->to_be('bar');
    }

    /**
     * @test
     */
    public function it_shortens_the_path()
    {
        expect((new Path(ds('foo', 'bar')))->shorten()->path())->to_be('bar');
    }

    /**
     * @test
     */
    public function it_skips_the_name()
    {
        expect((new Path('foo'))->skipName())->to_be('foo');
        expect((new Path(ds('foo', 'bar')))->skipName())->to_be('foo');
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

    /**
     * @test
     */
    public function it_resolves_path()
    {
        $n = ds();

        expect((new Path("..{$n}fs{$n}.{$n}{$n}..{$n}fs"))->full()->resolve()->path())
            ->to_be(getcwd());
    }

    /**
     * @test
     */
    public function it_clones_the_instance()
    {
        $path = new Path('foo');
        $replica = $path->replica();

        expect($path)->not_to_be($replica);
        expect($path->path())->to_be($replica->path());
    }
}
