<?php
include '../../../connect.php';

$result = mysqli_query($conn, "SELECT * FROM blog ORDER BY created_at DESC");

$blogs = [];
while ($row = mysqli_fetch_assoc($result)) {
    $blogs[] = $row;
}

header('Content-Type: application/json');
echo json_encode($blogs);
?>
