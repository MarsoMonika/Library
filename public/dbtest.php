<?php
require_once __DIR__ . '/../classes/Database.php';

$db = new Database();

try {
    $conn = $db->connect();
    echo "Connected successfully!";
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}