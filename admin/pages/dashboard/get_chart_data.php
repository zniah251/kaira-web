<?php
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";
header('Content-Type: application/json');

$labels = [];
$revenue = [];
$orders = [];
$statusLabels = ['Pending','Confirmed','Shipping','Cancelled','Return'];
$statusCounts = [];
$users = [];

$currentYear = date('Y');
for ($i = 1; $i <= 12; $i++) {
    $labels[] = date('M', mktime(0, 0, 0, $i, 10));

    $queryRev = "SELECT SUM(totalfinal) as total FROM `orders` WHERE MONTH(create_at) = $i AND paystatus = 'Paid'";
    $resultRev = mysqli_query($conn, $queryRev);
    $revVal = 0;
    if ($resultRev) {
        $revRow = mysqli_fetch_assoc($resultRev);
        $revVal = (float)($revRow['total'] ?? 0);
    }
    $revenue[] = $revVal;

    $queryOrd = "SELECT COUNT(*) as total FROM `orders` WHERE MONTH(create_at) = $i";
    $resultOrd = mysqli_query($conn, $queryOrd);
    $ordVal = 0;
    if ($resultOrd) {
        $ordRow = mysqli_fetch_assoc($resultOrd);
        $ordVal = (int)($ordRow['total'] ?? 0);
    }
    $orders[] = $ordVal;

    $userVal = 0;
    $queryuser = "SELECT COUNT(*) as total FROM `users` WHERE MONTH(created_at) = $i AND YEAR(created_at) = $currentYear";
    $resultuser = mysqli_query($conn, $queryuser);
    $userVal = 0;
    if ($resultuser) {
        $userRow = mysqli_fetch_assoc($resultuser);
        $userVal = (int)($userRow['total'] ?? 0);
    }
    $users[] = $userVal;
}

foreach ($statusLabels as $status) {
    $queryStatus = "SELECT COUNT(*) as total FROM `orders` WHERE destatus = '$status'";
    $resultStatus = mysqli_query($conn, $queryStatus);
    $statusVal = 0;
    if ($resultStatus) {
        $statusRow = mysqli_fetch_assoc($resultStatus);
        $statusVal = (int)($statusRow['total'] ?? 0);
    }
    $statusCounts[] = $statusVal;
}




echo json_encode([
    "labels" => $labels,
    "revenue" => $revenue,
    "orders" => $orders,
    "statusLabels" => $statusLabels,
    "statusCounts" => $statusCounts,
    "users" => $users
]);