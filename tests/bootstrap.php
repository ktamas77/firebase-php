<?php
// turn on all errors
error_reporting(E_ALL);
// autoloader
require dirname(__DIR__) . '/autoload.php';
// default globals
if (is_readable(__DIR__ . '/globals.dist.php')) {
    require __DIR__ . '/globals.dist.php';
}
// override globals
if (is_readable(__DIR__ . '/globals.php')) {
    require __DIR__ . '/globals.php';
}