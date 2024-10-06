<?php
spl_autoload_register(function ($className) {
    // Define the base directory where your package files are located
    $baseDir = __DIR__ . '/stripe-php-master/';

    // Convert namespace separators to directory separators
    $className = str_replace('\\', '/', $className);

    // Build the full path to the class file
    $file = $baseDir . $className . '.php';

    // If the class file exists, include it
    if (file_exists($file)) {
        require $file;
    }
});
