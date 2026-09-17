<?php

/**
 * Example Database Connection Configuration
 *
 * Copy this file to:
 *
 * config/database.php
 *
 * Then update the database credentials for the local
 * MySQL installation before running the application.
 *
 * @return PDO Returns an active PDO database connection.
 * @throws PDOException If the database connection fails.
 */

$host = 'localhost';
$dbName = 'alzikrayat';
$username = 'root';
$password = '';

$dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $exception) {
    die("Database connection failed.");
}
