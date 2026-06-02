<?php
require 'backend/config/db.php';
try {
    $pdo = getPDO();
    // Update existing data first so they fit the new ENUM
    $pdo->exec("UPDATE projects SET category = 'Wedding' WHERE category = 'Events & Wedding'");
    // Update schema
    $pdo->exec("ALTER TABLE projects MODIFY COLUMN category ENUM('General','Business','Wedding') NOT NULL DEFAULT 'General'");
    echo "Database schema updated successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
