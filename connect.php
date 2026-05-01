<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-web";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    error_log("Kết nối thất bại: " . $conn->connect_error);
    $conn = false;
}
?>
