<?php

/**
 * APP_URL and DB_PATH Initialisation
 *
 * - Builds APP_URL dynamically from protocol, host, and project path
 * - Ensures APP_URL is defined only once
 * - Defines DB_PATH for database connection file
 * - Normalises paths for cross‑platform compatibility
 */


if (!defined('APP_URL')) {

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        ? "https://"
        : "http://";

    $host = $_SERVER['HTTP_HOST'];

    $docRoot = realpath($_SERVER['DOCUMENT_ROOT']);
    $appRoot = realpath(APP_ROOT);

    $basePath = str_replace($docRoot, '', $appRoot);
    $basePath = str_replace('\\', '/', $basePath);

    define('APP_URL', rtrim($protocol . $host . $basePath, '/'));
}

if (!defined('DB_PATH')) {
    define('DB_PATH', APP_ROOT . '/Backend/db.php');
}