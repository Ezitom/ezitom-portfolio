<?php
ini_set('display_errors', 0);
error_reporting(0);

// ── DATABASE CONFIGURATION ────────────────────────────────────
// Automatically switch between Local (XAMPP) and Production (InfinityFree)
$isLocal = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['REMOTE_ADDR'] === '127.0.0.1');

if ($isLocal) {
    $host     = 'localhost';
    $dbname   = 'devfolio_db';
    $username = 'root';
    $password = '';
} else {
    // ⚠️ UPDATE THESE with your InfinityFree details from your Client Area
    $host     = 'sqlXXX.infinityfree.com'; 
    $dbname   = 'if0_XXXXXX_devfolio_db'; 
    $username = 'if0_XXXXXX';
    $password = 'Your_Infinity_Password';
}

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
