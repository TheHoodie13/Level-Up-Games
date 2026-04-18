<?php

/* Bootstrap Loader

 - Defines APP_ROOT for path resolution
 - Loads config and the autoloader
 */

 // Prevent rerunning
if (defined('BOOTSTRAPPED')) {
    return;
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

define('BOOTSTRAPPED', true);

require_once APP_ROOT . '/config/config.php';
require_once APP_ROOT . '/config/autoloader.php';
