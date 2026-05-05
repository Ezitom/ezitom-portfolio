<?php
ini_set('display_errors', 0);
error_reporting(0);

// Database credentials
$host     = 'localhost';
$dbname   = 'devfolio_db'; // Matches setup.sql
$username = 'root';
$password = '';

// Note: mysqli connection removed in favor of standardized PDO.


/**
 * Returns a singleton PDO instance (used by Projects, Skills, and Admin dashboard).
 */
function getPDO(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $dsn = "mysql:host=localhost;dbname=devfolio_db;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, 'root', '', $options);
    } catch (PDOException $e) {
        error_log('Database connection failed: ' . $e->getMessage());
        throw new Exception('Database connection failed.');
    }

    return $pdo;
}
