<?php

require_once __DIR__ . "/db.php";

$db = new Database();
$conn = $db->connect();

if ($conn) {
    echo "Database connected successfully!";
} else {
    echo "Connection failed!";
}