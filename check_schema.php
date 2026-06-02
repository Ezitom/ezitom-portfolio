<?php
require 'backend/config/db.php';
$pdo = getPDO();
$res = $pdo->query("DESCRIBE projects")->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($res);
echo "</pre>";
