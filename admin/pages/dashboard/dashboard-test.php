<?php
session_start();
// Kiểm tra xem người dùng đã đăng nhập chưa
// Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
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
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";
function safe_query_count($conn, $query, $key = 'total', $default = 0)
{
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row[$key] ?? $default;
    } else {
        return $default;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Dashboard</title>
    <<link rel="stylesheet" href="../../../admin/template/assets/vendors/mdi/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="../../../admin/template/assets/vendors/css/vendor.bundle.base.css">
        <link rel="stylesheet" href="../../../admin/template/assets/css/style.css">
         <link rel="stylesheet" href="../../template/assets/vendors/jvectormap/jquery-jvectormap.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <!-- Thêm vào trước thẻ </body> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.7.0/jspdf.plugin.autotable.min.js"></script>
</head>
<style>
    .body {
        font-family: 'Times New Roman', Times, serif;
        
    }

    canvas {
        max-width: 100% !important;
        height: 300px !important;
        font-family: 'Times New Roman', Times, serif;
    }

    .bg-black {
        font-family: 'Times New Roman', Times, serif;
    }

    #statusChart {
        display: block;
        margin: auto;
    }
</style>

</style>

<body>
    <div class="container-scroller" style="font-family: 'Times New Roman', Times, serif;">
        <?php include('../../template/sidebar.php'); ?>
        <div class="container-fluid page-body-wrapper">
            <?php include('../../template/navbar.php'); ?>
            <div class="main-panel w-full">
                <div class="content-wrapper p-4">

                    <!-- Summary Widgets -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <?php
                        $orderCount = safe_query_count($conn, "SELECT COUNT(*) AS total FROM `orders`");
                        $userCount = safe_query_count($conn, "SELECT COUNT(*) AS total FROM users");
                        $productCount = safe_query_count($conn, "SELECT COUNT(*) AS total FROM product WHERE stock > 0");
                        $revenue = safe_query_count($conn, "SELECT SUM(totalfinal) AS total FROM `orders` WHERE paystatus = 'Paid'");
                        ?>
                        <div class="bg-black p-4 rounded shadow" style="background-color: #b3a3ba !important; ">
                            <h3 class="text-sm font-medium">Tổng đơn hàng</h3>
                            <p class="text-2xl font-bold" style="color: #44334a;"><?= $orderCount ?></p>
                        </div>
                        <div class="bg-black p-4 rounded shadow" style="background-color: #b3a3ba !important;">
                            <h3 class="text-sm font-medium">Tổng người dùng</h3>
                            <p class="text-2xl font-bold" style="color: #44334a;"><?= $userCount ?></p>
                        </div>
                        <div class="bg-black p-4 rounded shadow" style="background-color: #b3a3ba !important;">
                            <h3 class="text-sm font-medium">Sản phẩm đang bán</h3>
                            <p class="text-2xl font-bold" style="color: #44334a;"><?= $productCount ?></p>
                        </div>
                        <div class="bg-black p-4 rounded shadow" style="background-color: #b3a3ba !important;">
                            <h3 class="text-sm font-medium">Tổng doanh thu</h3>
                            <p class="text-2xl font-bold" style="color: #44334a;"><?= number_format($revenue, 0) ?> VNĐ </p>
                        </div>
                    </div>

                    <!-- Biểu đồ kết hợp và Pie chart cạnh nhau -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-black p-4 rounded shadow" style="background-color: #b3a3ba !important;">
                            <h2 class="text-lg font-bold mb-2">Doanh thu và đơn hàng theo tháng</h2>
                            <canvas id="revenueChart"></canvas>
                        </div>
                        <div class="bg-black p-4 rounded shadow" style="background-color: #b3a3ba !important;">
                            <h2 class="text-lg font-bold mb-2">Trạng thái đơn hàng</h2>
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                    <!-- Biểu đồ người dùng mới và Sản phẩm bán chạy nhất cạnh nhau -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-black p-4 rounded shadow" style="background-color: #b3a3ba !important;">
                            <h2 class="text-lg font-bold mb-2">Người dùng mới</h2>
                            <canvas id="userChart"></canvas>
                        </div>
                        <div class="bg-black p-4 rounded shadow" style="background-color: #b3a3ba !important; color: #46315c;">
                            <h2 class="text-lg font-bold mb-2">Sản phẩm bán chạy</h2>
                            <div class="grid g rid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <?php
                                $bestSeller = mysqli_query($conn, "SELECT title, thumbnail, stock, sold FROM product ORDER BY sold DESC LIMIT 5");
                                while ($row = mysqli_fetch_assoc($bestSeller)) {
                                    echo '<div class="flex gap-4 items-center border p-2 rounded">';
                                    echo '<img src="../../../admin/assets/images/' . $row['thumbnail'] . '" alt="thumb" class="w-16 h-16 object-cover rounded">';
                                    echo '<div><p class="font-semibold">' . htmlspecialchars($row['title']) . '</p>';
                                    echo '<p>Đã bán: ' . $row['sold'] . '</p><p>Tồn kho: ' . $row['stock'] . '</p></div></div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Người dùng mới -->
                    <div class="bg-black p-4 rounded shadow mb-6" style="background-color: #b3a3ba !important;">
                        <h2 class="text-lg font-bold mb-2">Khách hàng mua nhiều nhất</h2>
                        <table class="w-full text-left" style="color: #46315c;">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2">Tên</th>
                                    <th>Email</th>
                                    <th>Tổng giá trị đơn hàng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $users = mysqli_query($conn, "
    SELECT u.uname, u.email, SUM(o.totalfinal) as spent
    FROM users u
    INNER JOIN orders o ON u.uid = o.uid
    GROUP BY u.uid
    ORDER BY spent DESC
    LIMIT 7
");
                                while ($u = mysqli_fetch_assoc($users)) {
                                    echo '<tr class="border-b"><td class="py-2">' . htmlspecialchars($u['uname']) . '</td><td>' . $u['email'] . '</td><td>' . number_format($u['spent'], 0) . '</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Sản phẩm gần hết hàng -->
                    <div class="bg-black p-4 rounded shadow mb-6" style="background-color: #b3a3ba !important;">
                        <h2 class="text-lg font-bold mb-2">Sản phẩm gần hết hàng</h2>
                        <table class="w-full text-left" style="color: #46315c;">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2">Tên</th>
                                    <th>Stock</th>
                                    <th>Màu</th>
                                    <th>Size</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lowStock = mysqli_query($conn, "SELECT title, stock, color, size FROM product WHERE stock <= 5 ORDER BY stock ASC");
                                while ($p = mysqli_fetch_assoc($lowStock)) {
                                    echo '<tr class="border-b"><td class="py-2">' . htmlspecialchars($p['title']) . '</td><td>' . $p['stock'] . '</td><td>' . $p['color'] . '</td><td>' . $p['size'] . '</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>



                    <script>
                        document.addEventListener('DOMContentLoaded', async () => {
                            const res = await fetch('get_chart_data.php');
                            const text = await res.text();
                            console.log('API response:', text);
                            let data;
                            try {
                                data = JSON.parse(text);
                            } catch (e) {
                                console.error('JSON parse error', e);
                                return;
                            }

                            // Gộp doanh thu và đơn hàng vào 1 biểu đồ kết hợp
                            new Chart(document.getElementById('revenueChart'), {
                                data: {
                                    labels: data.labels,
                                    datasets: [{
                                            type: 'bar',
                                            label: 'Doanh thu',
                                            data: data.revenue,
                                            backgroundColor: '#8d77a8',
                                            yAxisID: 'y',
                                        },
                                        {
                                            type: 'line',
                                            label: 'Đơn hàng',
                                            data: data.orders,
                                            borderColor: '#44334a',
                                            backgroundColor: '#44334a',
                                            fill: false,
                                            yAxisID: 'y1',
                                            tension: 0.3
                                        }
                                    ]
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            labels: {
                                                color: '#46315c'
                                            } // Đổi màu chữ chú thích thành trắng
                                        }
                                    },
                                    scales: {
                                        x: {
                                            ticks: {
                                                color: '#46315c'
                                            }
                                        }, // Đổi màu chữ trục X thành trắng
                                        y: {
                                            type: 'linear',
                                            position: 'left',
                                            title: {
                                                display: true,
                                                text: 'Doanh thu',
                                                color: '#46315c'
                                            },
                                            ticks: {
                                                color: '#46315c'
                                            } // Đổi màu chữ trục Y thành trắng
                                        },
                                        y1: {
                                            type: 'linear',
                                            position: 'right',
                                            title: {
                                                display: true,
                                                text: 'Đơn hàng',
                                                color: '#46315c'
                                            },
                                            grid: {
                                                drawOnChartArea: false
                                            },
                                            ticks: {
                                                color: '#46315c'
                                            }
                                        }
                                    }
                                }
                            });

                            // Trạng thái đơn hàng
                            new Chart(document.getElementById('statusChart'), {
                                type: 'pie',
                                data: {
                                    labels: data.statusLabels,
                                    datasets: [{
                                        data: data.statusCounts.map(x => x === 0 ? 0.001 : x), // tránh không vẽ nếu toàn 0
                                        backgroundColor: ['#d9d8d9', '#b3a3ba', '#8d769a', '#68507b', '#46315c']
                                    }]
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            labels: {
                                                color: '#46315c'
                                            } // Đổi màu chữ chú thích thành trắng
                                        }
                                    }
                                }
                            });
                            new Chart(document.getElementById('userChart'), {
                                type: 'bar',
                                data: {
                                    labels: data.labels, // dùng nhãn tháng giống như các biểu đồ khác
                                    datasets: [{
                                        label: 'Người dùng mới',
                                        data: data.users, // truyền đúng dữ liệu số lượng user theo tháng
                                        backgroundColor: '#8d769a',
                                        borderColor: '#8d769a',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            labels: {
                                                color: '#46315c'
                                            } // Đổi màu chữ chú thích thành trắng
                                        }
                                    },
                                    scales: {
                                        x: {
                                            ticks: {
                                                color: '#46315c'
                                            }
                                        }, // Đổi màu chữ trục X thành trắng
                                        y: {
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Người dùng mới',
                                                color: '#46315c'
                                            },
                                            ticks: {
                                                color: '#46315c'
                                            } // Đổi màu chữ trục Y thành trắng
                                        }
                                    }
                                }
                            });
                        });
                    </script>


                </div>
            </div>
        </div>
    </div>
</body>
<script src="../../template/assets/vendors/js/vendor.bundle.base.js"></script>

</html>