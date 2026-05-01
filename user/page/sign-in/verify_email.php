
<?php
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";

header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
$email = strtolower(trim($data['email'] ?? ''));

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu email']);
    exit;
}

$stmt = $conn->prepare("SELECT uid FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Email chưa được liên kết với tài khoản nào, vui lòng thử lại.']);
    exit;
}

echo json_encode(['success' => true]);
exit;