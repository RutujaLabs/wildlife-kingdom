<?php
/**
 * Database connection
 * Used by every page that needs to read/write from MySQL.
 */

$db_url = getenv('DB_URL');

if ($db_url) {
    $parsed = parse_url($db_url);

    $host = $parsed['host'] ?? '127.0.0.1';
    $db_port = isset($parsed['port']) ? (int) $parsed['port'] : 3306;
    $db_name = isset($parsed['path']) ? ltrim($parsed['path'], '/') : 'wildlife_kingdom';
    $db_user = $parsed['user'] ?? 'root';
    $db_pass = $parsed['pass'] ?? '';
} else {
    $host = getenv('DB_HOST') ?: '127.0.0.1';

    $db_port = getenv('DB_PORT');
    $db_port = $db_port === false || $db_port === '' ? 3306 : (int) $db_port;

    $db_name = getenv('DB_DATABASE') ?: 'wildlife_kingdom';
    $db_user = getenv('DB_USERNAME') ?: 'root';

    $db_pass = getenv('DB_PASSWORD');
    $db_pass = $db_pass === false ? '' : $db_pass;
}

$conn = new mysqli($host, $db_user, $db_pass, $db_name, $db_port);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');