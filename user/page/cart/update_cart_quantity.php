<?php
session_start();
require_once "../../../connect.php";
header('Content-Type: application/json');

// ── Kiểm tra đăng nhập ──────────────────────────────────────────────────────
if (!isset($_SESSION['uid']) || (int)$_SESSION['uid'] <= 0) {
    echo json_encode(['success' => false, 'error' => 'Chưa đăng nhập']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

$uid      = (int)$_SESSION['uid'];
// key format: "{pid}_{size}_{color}" – dùng để tìm đúng dòng trong DB
$key      = trim($_POST['key']      ?? '');
$quantity = (int)($_POST['quantity'] ?? 0);

if ($quantity <= 0 || $key === '') {
    echo json_encode(['success' => false, 'error' => 'Invalid key or quantity']);
    exit();
}

// Phân tích key thành pid, size, color
// key được tạo ở add_to_cart.php (cũ dùng session), giờ ta vẫn giữ format này
// để cart.php có thể truyền xuống qua data-key
$parts = explode('_', $key, 3);   // pid _ size _ color
if (count($parts) < 3) {
    echo json_encode(['success' => false, 'error' => 'Invalid key format']);
    exit();
}
$pid   = (int)$parts[0];
$size  = $parts[1] === 'nosize'  ? '' : $parts[1];
$color = $parts[2] === 'nocolor' ? '' : $parts[2];

try {
    // ── Kiểm tra stock trước khi cho phép tăng số lượng ─────────────────────
    $stmt_stock = $conn->prepare("SELECT stock FROM `product` WHERE pid = ?");
    if (!$stmt_stock) throw new Exception($conn->error);
    $stmt_stock->bind_param("i", $pid);
    $stmt_stock->execute();
    $product = $stmt_stock->get_result()->fetch_assoc();
    $stmt_stock->close();

    if (!$product) {
        echo json_encode(['success' => false, 'error' => 'Sản phẩm không tồn tại']);
        exit();
    }

    if ($quantity > (int)$product['stock']) {
        echo json_encode([
            'success' => false,
            'error'   => 'Vượt quá tồn kho (' . $product['stock'] . ')'
        ]);
        exit();
    }

    // ── Cập nhật số lượng trong bảng cart ───────────────────────────────────
    $stmt_update = $conn->prepare(
        "UPDATE `cart` SET quantity = ?, create_at = NOW()
         WHERE uid = ? AND pid = ? AND size = ? AND color = ?"
    );
    if (!$stmt_update) throw new Exception($conn->error);
    $stmt_update->bind_param("iiiss", $quantity, $uid, $pid, $size, $color);
    $stmt_update->execute();
    $stmt_update->close();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    error_log("update_cart_quantity error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} finally {
    if (isset($conn)) $conn->close();
}