<?php
require_once '../../../connect.php';

// Get collection ID from URL parameter, default to 2 (COLLECTIONS)
$collection_id = isset($_GET['id']) ? (int)$_GET['id'] : 2;

// First get collection info
$sql = "SELECT * FROM category WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $collection_id);
$stmt->execute();
$result = $stmt->get_result();
$collection = $result->fetch_assoc();

// If collection not found, show error
if (!$collection) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Collection not found</div></div>";
    exit;
}

// Get products in this collection
$sql = "SELECT p.*, c.cname as category_name 
        FROM product p 
        JOIN category c ON p.cid = c.cid 
        WHERE c.parentid = ? OR c.cid = ?
        ORDER BY p.pid DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $collection_id, $collection_id);
$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($collection['cname']); ?> - E-Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .card {
            transition: transform 0.2s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .text-danger {
            font-weight: bold;
        }

        .text-decoration-line-through {
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <?php include '../../../navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4"><?php echo htmlspecialchars($collection['cname']); ?></h1>

        <?php if ($products->num_rows > 0): ?>
            <div class="row">
                <?php while ($product = $products->fetch_assoc()): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <img src="../../../images/<?php echo htmlspecialchars($product['thumbnail']); ?>"
                                class="card-img-top"
                                alt="<?php echo htmlspecialchars($product['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h5>
                                <p class="card-text">
                                    <span class="text-muted"><?php echo htmlspecialchars($product['category_name']); ?></span><br>
                                    <span class="text-danger"><?php echo number_format($product['discount']); ?> VNĐ</span>
                                    <?php if ($product['discount'] < $product['price']): ?>
                                        <small class="text-muted text-decoration-line-through"><?php echo number_format($product['price']); ?> VNĐ</small>
                                    <?php endif; ?>
                                </p>
                                <a href="../product/detail.php?id=<?php echo $product['pid']; ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No products found in this collection.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>