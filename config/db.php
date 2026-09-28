<?php
/**
 * Database connection
 * Used by every page that needs to read/write from MySQL.
 */

$host    = 'localhost';
$db_name = 'wildlife_kingdom';
$db_user = 'root';
$db_pass = ''; // XAMPP default: no password

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');