<?php

spl_autoload_register(function ($class) {
    $baseDir = __DIR__;
    $class = str_replace('\\', '/', $class);

    $classFile = $baseDir . '/' . $class . '.php';

    if (file_exists($classFile)) {
        require $classFile;
    }
});
