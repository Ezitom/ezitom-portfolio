<?php
require 'backend/config/db.php';
$pdo = getPDO();
echo "--- CATEGORIES ---\n";
$res = $pdo->query("SELECT DISTINCT category FROM skills")->fetchAll(PDO::FETCH_COLUMN);
print_r($res);
echo "--- SKILL NAMES ---\n";
$res = $pdo->query("SELECT DISTINCT skill_name FROM skills")->fetchAll(PDO::FETCH_COLUMN);
print_r($res);
