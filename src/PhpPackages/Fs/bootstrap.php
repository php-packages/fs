<?php

if ( ! defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if ( ! function_exists('ds')) {
    function ds() {
        return DIRECTORY_SEPARATOR;
    }
}

if ( ! function_exists('path')) {
    function path($path) {
        return new PhpPackages\Fs\Path($path);
    }
}
