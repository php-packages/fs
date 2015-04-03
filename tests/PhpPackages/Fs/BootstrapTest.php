<?php namespace PhpPackages\Fs;

class BootstrapTest extends \TestCase {

    /**
     * @test
     */
    public function it_provides_helper_functions()
    {
        expect(ds())->to_be(DIRECTORY_SEPARATOR);
        expect(ds('foo', 'bar', 'baz'))->to_be('foo' . ds() . 'bar' . ds() . 'baz');

        expect(path('foo'))->to_be_a('PhpPackages\\Fs\\Path');
        expect((string) path(path('foo')))->to_be('foo');
    }
}
