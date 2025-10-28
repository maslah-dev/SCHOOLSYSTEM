<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'school_system');

function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
    }

    $conn->set_charset('utf8');
    return $conn;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
 * API / CORS headers
 * These are safe-guarded so they don't interfere when running from CLI.
 */
if (php_sapi_name() !== 'cli') {
    // only set JSON Content-Type for API responses unless caller requests otherwise
    if (!defined('SKIP_JSON_HEADER')) {
        header('Content-Type: application/json; charset=utf-8');
    }
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // preflight request; exit early
        exit(0);
    }
}
?>