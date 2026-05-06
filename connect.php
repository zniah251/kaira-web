<?php

$servername = getenv('DB_HOST') ?: "localhost";
$username   = getenv('DB_USER') ?: "root";
$password   = getenv('DB_PASSWORD') ?: "";
$dbname     = getenv('DB_NAME') ?: "e-web";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    error_log("Kết nối thất bại: " . $conn->connect_error);
    $conn = false;
}
?>