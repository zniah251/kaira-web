<?php
session_start(); // Bắt đầu session để lưu trữ giỏ hàng

require_once "../../../connect.php"; // Kết nối đến database của bạn

header('Content-Type: application/json'); // Đảm bảo trả về JSON

// Kiểm tra xem yêu cầu có phải là POST không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ $_POST (được gửi bởi FormData của JavaScript)
    $pid = filter_var($_POST['pid'] ?? null, FILTER_SANITIZE_NUMBER_INT);
    $title = htmlspecialchars(trim($_POST['title'] ?? ''));
    $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
    $thumbnail = htmlspecialchars(trim($_POST['thumbnail'] ?? ''));
    $quantity_to_add = filter_var($_POST['quantity'] ?? 1, FILTER_SANITIZE_NUMBER_INT); // Số lượng muốn thêm vào giỏ
    $size = htmlspecialchars(trim($_POST['size'] ?? ''));
    $color = htmlspecialchars(trim($_POST['color'] ?? ''));

    // Kiểm tra dữ liệu cơ bản
    if (!$pid || !$title || $price === false || $quantity_to_add <= 0) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu sản phẩm không hợp lệ.']);
        $conn->close(); // Đóng kết nối DB
        exit();
    }

    try {
        // --- Bắt đầu kiểm tra Stock từ Database ---
        // Truy vấn stock hiện tại của sản phẩm
        // Giả định stock nằm trong bảng `product`.
        // Nếu bạn quản lý stock theo biến thể (size/color) trong một bảng riêng (ví dụ: product_variants),
        // bạn cần điều chỉnh truy vấn JOIN và điều kiện WHERE cho phù hợp.
        
        $stmt_stock = $conn->prepare("SELECT stock FROM `product` WHERE pid = ?");
        if (!$stmt_stock) {
            throw new Exception("Lỗi prepare statement lấy stock: " . $conn->error);
        }
        $stmt_stock->bind_param("i", $pid);
        $stmt_stock->execute();
        $result_stock = $stmt_stock->get_result();
        $product_db_data = $result_stock->fetch_assoc();
        $stmt_stock->close();

        if (!$product_db_data) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại trong hệ thống.']);
            $conn->close();
            exit();
        }

        $current_stock = $product_db_data['stock'];

        // Khởi tạo giỏ hàng nếu chưa tồn tại
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Tạo một "ID duy nhất" cho mỗi biến thể sản phẩm trong giỏ hàng
        $item_unique_key = $pid . '_' . ($size ?: 'nosize') . '_' . ($color ?: 'nocolor'); // Dùng ?: thay cho ?? để tránh lỗi trên PHP cũ hơn

        $existing_quantity_in_cart = 0;
        if (isset($_SESSION['cart'][$item_unique_key])) {
            $existing_quantity_in_cart = $_SESSION['cart'][$item_unique_key]['quantity'];
        }

        $total_quantity_desired = $existing_quantity_in_cart + $quantity_to_add;

        // 1. Kiểm tra nếu stock = 0
        if ($current_stock <= 0) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm "' . $title . '" đã hết hàng.']);
            $conn->close();
            exit();
        }

        // 2. Kiểm tra nếu số lượng muốn thêm vượt quá stock hiện có
        if ($total_quantity_desired > $current_stock) {
            echo json_encode(['success' => false, 'message' => 'Số lượng bạn muốn thêm (' . $quantity_to_add . ') sẽ làm tổng số lượng trong giỏ hàng vượt quá số lượng tồn kho hiện có (' . $current_stock . '). Số lượng hiện có trong giỏ: ' . $existing_quantity_in_cart]);
            $conn->close();
            exit();
        }

        // --- Kết thúc kiểm tra Stock ---


        // Nếu mọi kiểm tra đều qua, tiến hành thêm/cập nhật giỏ hàng
        if (isset($_SESSION['cart'][$item_unique_key])) {
            $_SESSION['cart'][$item_unique_key]['quantity'] = $total_quantity_desired; // Cập nhật tổng số lượng
        } else {
            $_SESSION['cart'][$item_unique_key] = [
                'pid' => $pid,
                'title' => $title,
                'price' => $price,
                'thumbnail' => $thumbnail,
                'quantity' => $quantity_to_add,
                'size' => $size,
                'color' => $color
            ];
        }

        // Tính tổng số lượng sản phẩm trong giỏ để cập nhật UI
        $total_items_in_cart = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_items_in_cart += $item['quantity'];
        }

        echo json_encode([
            'success' => true,
            'message' => 'Đã thêm "' . $title . '" vào giỏ hàng!',
            'total_items_in_cart' => $total_items_in_cart
        ]);

    } catch (Exception $e) {
        error_log("Lỗi khi thêm vào giỏ hàng: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi hệ thống: ' . $e->getMessage()]);
    } finally {
        // Đảm bảo kết nối database luôn được đóng
        if ($conn) {
            $conn->close();
        }
    }

} else {
    echo json_encode([
        'success' => false,
        'message' => 'Yêu cầu không hợp lệ. Chỉ chấp nhận phương thức POST.'
    ]);
}
?>