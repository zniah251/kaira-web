<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";

header('Content-Type: application/json');

// Kiểm tra đăng nhập và quyền admin

// Kiểm tra CSRF token (thêm sau khi bạn đã implement CSRF protection)
// if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
//     echo json_encode([
//         'success' => false,
//         'message' => 'Invalid security token'
//     ]);
//     exit;
// }

// Kiểm tra xem có dữ liệu POST và pid được gửi không
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pid'])) {
    $pid = intval($_POST['pid']);
    
    try {
        // Kiểm tra xem sản phẩm có tồn tại không
        $check_sql = "SELECT pid FROM product WHERE pid = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $pid);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Product not found'
            ]);
            exit;
        }
        $check_stmt->close();

        // Bắt đầu transaction
        $conn->begin_transaction();

        // Xóa các ảnh liên quan (nếu cần)
        // TODO: Thêm code xóa file ảnh từ thư mục nếu cần

        // Xóa sản phẩm
        $delete_sql = "DELETE FROM product WHERE pid = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $pid);
        
        if ($stmt->execute()) {
            // Commit transaction nếu thành công
            $conn->commit();
            
            // Ghi log
            $admin_id = $_SESSION['admin_id'] ?? 0;
            $log_sql = "INSERT INTO activity_log (admin_id, action, details) VALUES (?, 'delete_product', ?)";
            $log_stmt = $conn->prepare($log_sql);
            $details = "Deleted product ID: " . $pid;
            $log_stmt->bind_param("is", $admin_id, $details);
            $log_stmt->execute();
            $log_stmt->close();

            echo json_encode([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } else {
            // Rollback nếu có lỗi
            $conn->rollback();
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete product'
            ]);
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        // Rollback nếu có bất kỳ lỗi nào
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}

$conn->close();
?> 