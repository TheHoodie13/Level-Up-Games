<?php

/*Autoloader for classes in the project

 - Uses spl_autoload_register to automatically include class files
 - Creates custom prefixes (I struggled with adapting code to new format)
 - Logs errors if class files are not found, aiding debugging
 */


spl_autoload_register(function ($class) {

    $prefixes = [
        'Frontend\\' => APP_ROOT . '/src/Frontend/',
        'Backend\\'  => APP_ROOT . '/src/Backend/',
        'config\\' => APP_ROOT . '/src/config/',
        'assets\\' => APP_ROOT . '/src/assets/',
        'logs\\' => APP_ROOT . '/src/logs/'
    ];

    foreach ($prefixes as $prefix => $baseDir) {

        if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
            continue;
        }

        $relativeClass = substr($class, strlen($prefix));

        $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

        if (file_exists($file)) {
            require_once $file;
            return true;
        }

        error_log("Autoloader: Class '$class' not found at '$file'");
    }

    return false;
});