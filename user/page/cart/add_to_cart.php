<?php
session_start();
require_once "../../../connect.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ. Chỉ chấp nhận phương thức POST.']);
    exit();
}

// ── Kiểm tra đăng nhập ──────────────────────────────────────────────────────
if (!isset($_SESSION['uid']) || (int)$_SESSION['uid'] <= 0) {
    echo json_encode([
        'success'     => false,
        'notLoggedIn' => true,
        'message'     => 'Bạn chưa đăng nhập. Vui lòng đăng nhập để thêm vào giỏ hàng.'
    ]);
    if (isset($conn)) $conn->close();
    exit();
}

$uid = (int)$_SESSION['uid'];

// ── Lấy & validate dữ liệu POST ─────────────────────────────────────────────
$pid              = filter_var($_POST['pid']      ?? null, FILTER_SANITIZE_NUMBER_INT);
$title            = htmlspecialchars(trim($_POST['title']     ?? ''));
$price            = filter_var($_POST['price']     ?? null, FILTER_VALIDATE_FLOAT);
$thumbnail        = htmlspecialchars(trim($_POST['thumbnail'] ?? ''));
$quantity_to_add  = filter_var($_POST['quantity']  ?? 1,    FILTER_SANITIZE_NUMBER_INT);
$size             = htmlspecialchars(trim($_POST['size']     ?? ''));
$color            = htmlspecialchars(trim($_POST['color']    ?? ''));

if (!$pid || !$title || $price === false || $quantity_to_add <= 0) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu sản phẩm không hợp lệ.']);
    $conn->close();
    exit();
}

try {
    // ── Kiểm tra stock ───────────────────────────────────────────────────────
    $stmt_stock = $conn->prepare("SELECT stock FROM `product` WHERE pid = ?");
    if (!$stmt_stock) throw new Exception("Lỗi prepare: " . $conn->error);
    $stmt_stock->bind_param("i", $pid);
    $stmt_stock->execute();
    $product_db = $stmt_stock->get_result()->fetch_assoc();
    $stmt_stock->close();

    if (!$product_db) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại trong hệ thống.']);
        $conn->close();
        exit();
    }

    $current_stock = (int)$product_db['stock'];

    if ($current_stock <= 0) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm "' . $title . '" đã hết hàng.']);
        $conn->close();
        exit();
    }

    // ── Kiểm tra xem item đã có trong bảng cart chưa ────────────────────────
    $stmt_check = $conn->prepare(
        "SELECT caid, quantity FROM `cart` WHERE uid = ? AND pid = ? AND size = ? AND color = ? LIMIT 1"
    );
    if (!$stmt_check) throw new Exception("Lỗi prepare: " . $conn->error);
    $stmt_check->bind_param("iiss", $uid, $pid, $size, $color);
    $stmt_check->execute();
    $existing = $stmt_check->get_result()->fetch_assoc();
    $stmt_check->close();

    $existing_qty = $existing ? (int)$existing['quantity'] : 0;
    $total_qty    = $existing_qty + $quantity_to_add;

    if ($total_qty > $current_stock) {
        echo json_encode([
            'success' => false,
            'message' => 'Số lượng muốn thêm (' . $quantity_to_add . ') vượt quá tồn kho hiện có (' . $current_stock . '). Số lượng hiện có trong giỏ: ' . $existing_qty
        ]);
        $conn->close();
        exit();
    }

    // ── Thêm hoặc cập nhật bảng cart ────────────────────────────────────────
    if ($existing) {
        // Cập nhật số lượng
        $stmt_update = $conn->prepare(
            "UPDATE `cart` SET quantity = ?, create_at = NOW() WHERE caid = ?"
        );
        if (!$stmt_update) throw new Exception("Lỗi prepare: " . $conn->error);
        $stmt_update->bind_param("ii", $total_qty, $existing['caid']);
        $stmt_update->execute();
        $stmt_update->close();
    } else {
        // Thêm mới
        $stmt_insert = $conn->prepare(
            "INSERT INTO `cart` (uid, pid, quantity, size, color, create_at) VALUES (?, ?, ?, ?, ?, NOW())"
        );
        if (!$stmt_insert) throw new Exception("Lỗi prepare: " . $conn->error);
        $stmt_insert->bind_param("iiiss", $uid, $pid, $quantity_to_add, $size, $color);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    // ── Tổng số lượng trong giỏ để cập nhật badge navbar ────────────────────
    $stmt_total = $conn->prepare("SELECT SUM(quantity) AS total FROM `cart` WHERE uid = ?");
    $stmt_total->bind_param("i", $uid);
    $stmt_total->execute();
    $total_items = (int)$stmt_total->get_result()->fetch_assoc()['total'];
    $stmt_total->close();

    echo json_encode([
        'success'             => true,
        'message'             => 'Đã thêm "' . $title . '" vào giỏ hàng!',
        'total_items_in_cart' => $total_items
    ]);

} catch (Exception $e) {
    error_log("Lỗi khi thêm vào giỏ hàng: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi hệ thống: ' . $e->getMessage()]);
} finally {
    if (isset($conn)) $conn->close();
}