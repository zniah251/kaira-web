<?php
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['oid']) || !isset($data['field']) || !isset($data['value'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

$oid = $data['oid'];
$field = $data['field'];
$value = $data['value'];

// Validate field name to prevent SQL injection
if (!in_array($field, ['destatus', 'paystatus'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid field']);
    exit;
}

// Validate status values
$valid_order_statuses = ['Pending', 'Confirmed', 'Shipping', 'Cancelled', 'Return', 'Delivered'];
$valid_payment_statuses = ['Pending', 'Paid', 'Awaiting refund', 'Refunded'];

if ($field === 'destatus' && !in_array($value, $valid_order_statuses)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid order status']);
    exit;
}

if ($field === 'paystatus' && !in_array($value, $valid_payment_statuses)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payment status']);
    exit;
}

// Update the database
$sql = "UPDATE orders SET $field = ? WHERE oid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $value, $oid);

if ($stmt->execute()) {
    // If order status is being changed to Cancelled
    if ($field === 'destatus' && $value === 'Cancelled') {
        // Get all products from the cancelled order
        $getProductsQuery = "SELECT od.pid, od.quantity, p.stock, p.sold 
                            FROM order_detail od 
                            JOIN product p ON od.pid = p.pid 
                            WHERE od.oid = ?";
        $stmt = $conn->prepare($getProductsQuery);
        $stmt->bind_param("i", $oid);
        $stmt->execute();
        $productsResult = $stmt->get_result();

        // Update stock and sold for each product
        while ($product = $productsResult->fetch_assoc()) {
            $newStock = $product['stock'] + $product['quantity'];
            $newSold = $product['sold'] - $product['quantity'];
            
            $updateProductQuery = "UPDATE product 
                                 SET stock = ?, sold = ? 
                                 WHERE pid = ?";
            $stmt = $conn->prepare($updateProductQuery);
            $stmt->bind_param("iii", $newStock, $newSold, $product['pid']);
            $stmt->execute();
        }
    }
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database update failed']);
} 