<?php

if ( ! function_exists('ds')) {
    function ds() {
        if (count(func_get_args()) < 1) {
            return DIRECTORY_SEPARATOR;
        }

        return implode(ds(), func_get_args());
    }
}

if ( ! function_exists('path')) {
    function path($path) {
        return new PhpPackages\Fs\Path($path);
    }
}
