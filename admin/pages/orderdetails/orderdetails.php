<?php
// orderdetails.php
session_start();
if (!isset($_SESSION['uid']) || $_SESSION['uid'] <= 0) {
    // Điều chỉnh đường dẫn đến trang đăng nhập của bạn
    header("Location: /e-web/user/page/sign-in/login2.php");
    exit();
}

// Kiểm tra quyền admin
if (!isset($_SESSION['rid']) || $_SESSION['rid'] != 1) {
    // Nếu không phải admin, chuyển hướng về trang chủ
    header("Location: /e-web/user/index.php");
    exit();
}
// 1. KẾT NỐI CƠ SỞ DỮ LIỆU
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";

// Get order ID from URL parameter
$oid = isset($_GET['oid']) ? $_GET['oid'] : null;

// Fetch order details with product information
$sql = "SELECT od.*, p.thumbnail, p.title 
        FROM order_detail od 
        LEFT JOIN product p ON od.pid = p.pid 
        WHERE od.oid = ?";

// Add new SQL to get order information including voucher and user details
$order_sql = "SELECT o.*, v.minprice as discount, u.uname, u.email, u.phonenumber, u.address, 
              DATE_ADD(o.create_at, INTERVAL 5 DAY) as shipping_date
              FROM orders o
              LEFT JOIN voucher v ON o.vid = v.vid
              LEFT JOIN users u ON o.uid = u.uid
              WHERE o.oid = ?";

$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $oid);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
$order_data = $order_result->fetch_assoc();

// Calculate total price from order details
$total_price = 0;
$shipping_cost = 30000;
$discount = $order_data['discount'] ?? 0;

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $oid);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Corona Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../../admin/template/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../../admin/template/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../../template/assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="../../template/assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../../template/assets/vendors/owl-carousel-2/owl.carousel.min.css">
    <link rel="stylesheet" href="../../template/assets/vendors/owl-carousel-2/owl.theme.default.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../../assets/product.css">
    <link rel="stylesheet" href="../../../admin/template/assets/css/style.css">
    <link rel="stylesheet" href="../../../admin/template/assets/css/maps/style.css.map">

    <!-- End layout styles -->
    <link rel="shortcut icon" href="../../../admin/template/assets/images/favicon.png" />
    <!-- Sử dụng liên kết CDN mới nhất của Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            /* Thêm fallback font */
        }
        .custom-select-box {
            width: 100%;
            padding: 8px 12px;
            background-color: #111;
            color: #fff;
            border: 1px solid #555;
            border-radius: 4px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .custom-select-box option {
            background-color: #111;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <?php include('../../template/sidebar.php'); ?>
        <div class="container-fluid page-body-wrapper">
            <?php include('../../template/navbar.php'); ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-8 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title"><strong>Order ID: <?php echo htmlspecialchars($order_data['oid']); ?></strong></h4>
                                    <h6 class="mb-0">Customer ID: <?php echo htmlspecialchars($order_data['uid']); ?></h6>
                                    <div class="table-responsive">
                                        <table class="table" style="min-width: 800px;">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="form-check form-check-muted m-0">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input">
                                                            </label>
                                                        </div>
                                                    </th>
                                                    <th> PRODUCTS </th>
                                                    <th> COLOR </th>
                                                    <th> SIZE </th>
                                                    <th> PRICE </th>
                                                    <th> QUANTITY </th>
                                                    <th> TOTAL </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td>
                                                        <div class="form-check form-check-muted m-0">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input">
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <?php
                                                            $img = $row['thumbnail'];
                                                            // Nếu đường dẫn bắt đầu bằng 'admin/assets/images/', loại bỏ phần này
                                                            if (strpos($img, 'admin/assets/images/') === 0) {
                                                                $img = substr($img, strlen('admin/assets/images/'));
                                                            }
                                                            // Đảm bảo không có khoảng trắng và mã hóa URL
                                                            $img_url = '/e-web/admin/assets/images/' . rawurlencode(trim($img));
                                                            ?>
                                                            <img src="<?php echo $img_url; ?>" alt="Thumbnail" style="width:40px;height:40px;object-fit:cover;border-radius:0;">
                                                            <div class="d-flex flex-column ps-2" style="min-width: 150px;">
                                                                <span><?php echo htmlspecialchars($row['title']); ?></span>
                                                                <span>MN<?php echo str_pad($row['pid'], 2, '0', STR_PAD_LEFT); ?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($row['color']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['size']); ?></td>
                                                    <td><?php echo number_format($row['price'], 0, ',', '.'); ?>đ</td>
                                                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                                    <td><?php echo number_format($row['price'] * $row['quantity'], 0, ',', '.'); ?>đ</td>
                                                </tr>
                                                <?php
                                                $total_price += $row['price'] * $row['quantity'];
                                                ?>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 grid-margin stretch-card">
                            <div class="card custom-summary-card" style="min-height: auto; height: auto;">
                                <div class="card-body">
                                    <h4 class="card-title"><strong>Summary</strong></h4>

                                    <div class="bg-gray-dark d-flex justify-content-between py-2 px-4 rounded mt-2">
                                        <h6 class="mb-0">Price :</h6>
                                        <h6 class="font-weight-bold mb-0"><?php echo number_format($total_price, 0, ',', '.'); ?>đ</h6>
                                    </div>
                                    <div class="bg-gray-dark d-flex justify-content-between py-2 px-4 rounded mt-2">
                                        <h6 class="mb-0">Discount :</h6>
                                        <h6 class="font-weight-bold mb-0 text-danger">-<?php echo number_format($discount, 0, ',', '.'); ?>đ</h6>
                                    </div>
                                    <div class="bg-gray-dark d-flex justify-content-between py-2 px-4 rounded mt-2">
                                        <h6 class="mb-0">Shipping Cost :</h6>
                                        <h6 class="font-weight-bold mb-0"><?php echo number_format($shipping_cost, 0, ',', '.'); ?>đ</h6>
                                    </div>

                                    <!-- Đường gạch ngang -->
                                    <hr class="my-2" style="border-top: 1px solid #ccc;" />

                                    <!-- TOTAL -->
                                    <div class="bg-gray-dark d-flex justify-content-between py-2 px-4 rounded mt-2">
                                        <h5 class="mb-0">Total :</h5>
                                        <h5 class="font-weight-bold mb-0"><?php echo number_format($total_price - $discount + $shipping_cost, 0, ',', '.'); ?>đ</h5>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-8 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row text-white">
                                        <!-- Billing details -->
                                        <div class="col-md-6">
                                            <h5 class="font-weight-bold mb-3">Billing details</h5>

                                            <div class="mb-2 d-flex align-items-start">
                                                <i class="mdi mdi-account-outline mr-2"></i>
                                                <div>
                                                    <small class="font-weight-bold">Customer</small><br />
                                                    <a href="#" class="text-blue-700"><?php echo htmlspecialchars($order_data['uname']); ?></a>
                                                </div>
                                            </div>

                                            <div class="mb-2 d-flex align-items-start">
                                                <i class="mdi mdi-email-outline mr-2"></i>
                                                <div>
                                                    <small class="font-weight-bold">Email</small><br />
                                                    <span class="text-blue-700"><?php echo htmlspecialchars($order_data['email']); ?></span>
                                                </div>
                                            </div>

                                            <div class="mb-2 d-flex align-items-start">
                                                <i class="mdi mdi-phone-outline mr-2"></i>
                                                <div>
                                                    <small class="font-weight-bold">Phone</small><br />
                                                    <span class="text-blue-700"><?php echo htmlspecialchars($order_data['phonenumber']); ?></span>
                                                </div>
                                            </div>

                                            <div class="mb-2 d-flex align-items-start">
                                                <i class="mdi mdi-home-outline mr-2"></i>
                                                <div>
                                                    <small class="font-weight-bold">Address</small><br />
                                                    <span class="text-white"><?php echo nl2br(htmlspecialchars($order_data['address'])); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Shipping details -->
                                        <div class="col-md-6">
                                            <h5 class="font-weight-bold mb-3">Shipping details</h5>

                                            <div class="mb-2 d-flex align-items-start">
                                                <i class="mdi mdi-email-outline mr-2"></i>
                                                <div>
                                                    <small class="font-weight-bold">Email</small><br />
                                                    <span class="text-blue-700"><?php echo htmlspecialchars($order_data['email']); ?></span>
                                                </div>
                                            </div>

                                            <div class="mb-2 d-flex align-items-start">
                                                <i class="mdi mdi-phone-outline mr-2"></i>
                                                <div>
                                                    <small class="font-weight-bold">Phone</small><br />
                                                    <span class="text-blue-700"><?php echo htmlspecialchars($order_data['phonenumber']); ?></span>
                                                </div>
                                            </div>

                                            <div class="mb-2 d-flex align-items-start">
                                                <i class="mdi mdi-calendar-blank-outline mr-2"></i>
                                                <div>
                                                    <small class="font-weight-bold">Shipping Date</small><br />
                                                    <span class="text-white"><?php echo date('d M, Y', strtotime($order_data['shipping_date'])); ?></span>
                                                </div>
                                            </div>

                                            <div class="mb-2 d-flex align-items-start">
                                                <i class="mdi mdi-home-outline mr-2"></i>
                                                <div>
                                                    <small class="font-weight-bold">Address</small><br />
                                                    <span class="text-white"><?php echo nl2br(htmlspecialchars($order_data['address'])); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 grid-margin stretch-card">
                            <div class="card custom-summary-card" style="min-height: auto; height: auto;">
                                <div class="card-body">
                                    <h4 class="font-weight-bold card-title">Order Summary</h4>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Order Status</label>
                                        <select id="orderStatus" class="custom-select-box">
                                            <option value="Pending" <?php echo $order_data['destatus'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Confirmed" <?php echo $order_data['destatus'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                            <option value="Shipping" <?php echo $order_data['destatus'] == 'Shipping' ? 'selected' : ''; ?>>Shipping</option>
                                            <option value="Cancelled" <?php echo $order_data['destatus'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            <option value="Return" <?php echo $order_data['destatus'] == 'Return' ? 'selected' : ''; ?>>Return</option>
                                            <option value="Delivered" <?php echo $order_data['destatus'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Payment Status</label>
                                        <select id="paymentStatus" class="custom-select-box">
                                            <option value="Pending" <?php echo $order_data['paystatus'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Paid" <?php echo $order_data['paystatus'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                                            <option value="Awaiting refund" <?php echo $order_data['paystatus'] == 'Awaiting refund' ? 'selected' : ''; ?>>Awaiting refund</option>
                                            <option value="Refunded" <?php echo $order_data['paystatus'] == 'Refunded' ? 'selected' : ''; ?>>Refunded</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Payment Method</label>
                                        <div class="text-white"><?php echo htmlspecialchars($order_data['paymethod']); ?></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                    <footer class="footer">
                        <div class="d-sm-flex justify-content-center justify-content-sm-between">
                            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright
                                ©
                                bootstrapdash.com 2021</span>
                            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a
                                    href="https://www.bootstrapdash.com/bootstrap-admin-template/"
                                    target="_blank">Bootstrap
                                    admin template</a> from Bootstrapdash.com</span>
                        </div>
                    </footer>
                    <!-- partial -->

                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../../template/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../../template/assets/vendors/chart.js/Chart.min.js"></script>
    <script src="../../template/assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="../../template/assets/js/misc.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderStatus = document.getElementById('orderStatus');
        const paymentStatus = document.getElementById('paymentStatus');
        const oid = <?php echo json_encode($oid); ?>;

        async function updateStatus(field, value) {
            try {
                const response = await fetch('update_order_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        oid: oid,
                        field: field,
                        value: value
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    // Show success message
                    alert('Status updated successfully');
                } else {
                    // Show error message
                    alert('Error updating status: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating status');
            }
        }

        // Add event listeners for status changes
        orderStatus.addEventListener('change', function() {
            updateStatus('destatus', this.value);
        });

        paymentStatus.addEventListener('change', function() {
            updateStatus('paystatus', this.value);
        });
    });
    </script>
</body>

</html>