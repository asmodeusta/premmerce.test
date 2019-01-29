<?php

function autoloader($classname) {
    $paths = [
        '/basic/',
        '/components/',
        '/models/',
    ];

    foreach ($paths as $path) {
        $filename = APP . $path . $classname . '.php';
        if (is_file($filename)) {
            include_once $filename;
            break;
        }
    }
}

spl_autoload_register('autoloader');