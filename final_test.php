<?php
require 'backend/config/db.php';

echo "--- DATABASE TEST ---\n";
try {
    $pdo = getPDO();
    echo "Connection: OK\n";
    
    // Check tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . implode(', ', $tables) . "\n";
    
    // Check projects category enum
    $res = $pdo->query("SHOW COLUMNS FROM projects LIKE 'category'")->fetch();
    echo "Projects Category Type: " . $res['Type'] . "\n";
    
    // Check counts
    $pCount = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    $sCount = $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
    $mCount = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
    echo "Counts: Projects($pCount), Skills($sCount), Messages($mCount)\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n--- API ENDPOINTS TEST ---\n";
$endpoints = ['projects.php', 'skills.php', 'messages.php'];
foreach ($endpoints as $ep) {
    $path = "backend/api/$ep";
    if (file_exists($path)) {
        echo "Endpoint $ep: EXISTS\n";
    } else {
        echo "Endpoint $ep: MISSING\n";
    }
}

echo "\n--- ADMIN DASHBOARD TEST ---\n";
$adminFiles = ['index.php', 'login.php', '../includes/header.php', '../includes/footer.php'];
foreach ($adminFiles as $f) {
    $path = "backend/admin/$f";
    if (file_exists($path)) {
        echo "Admin File $f: EXISTS\n";
    } else {
        echo "Admin File $f: MISSING\n";
    }
}
