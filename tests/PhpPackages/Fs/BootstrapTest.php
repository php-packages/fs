<?php namespace PhpPackages\Fs;

class BootstrapTest extends \TestCase {

    /**
     * @test
     */
    public function it_provides_helper_functions()
    {
        expect(function_exists('ds'))->to_be(true);
        expect(ds())->to_be(DIRECTORY_SEPARATOR);

        expect(function_exists('path'))->to_be(true);
        expect(path('foo'))->to_be_a('PhpPackages\\Fs\\Path');
    }
}
