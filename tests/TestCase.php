<?php

class TestCase extends Essence\Extensions\PhpunitExtension {

    /**
     * @return string
     */
    protected function getTestedClass()
    {
        return str_replace('Test', '', get_class($this));
    }

    /**
     * @return object
     */
    protected function makeFake()
    {
        $class = $this->getTestedClass();

        return new $class(uniqid());
    }

    /**
     * @param string|null $path
     * @return object
     */
    protected function makeReal($path = null)
    {
        $class = $this->getTestedClass();

        if (is_null($path)) {
            $path = __FILE__;
        }

        return new $class($path);
    }
}
