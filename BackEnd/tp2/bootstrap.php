<?php
spl_autoload_register(function ($class_name) {
    $classFile = $class_name . '.php';
    if (file_exists($classFile)) {
        include $classFile;
        return;
    }
    $dirs = array(
        '.',
        'controller',
        'model',
    );

    foreach ($dirs as $dir) {
        $classFile = $dir . '/' . $class_name . '.php';
        if (file_exists($classFile)) {
            include $classFile;
            return;
        }
    }
});
