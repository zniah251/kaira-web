<?php
session_start(); // Bắt đầu session ở đầu file
// Kiểm tra xem người dùng đã đăng nhập chưa
// Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
if (!isset($_SESSION['uid']) || $_SESSION['uid'] <= 0) {
    // Điều chỉnh đường dẫn đến trang đăng nhập của bạn
    header("Location: /e-web/user/page/sign-in/login2.php");
    exit();
}
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Kaira Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../../../user/css/vendor.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" type="text/css" href="abtus.css">
    <link rel="stylesheet" type="text/css" href="../../style.css">
    <link rel="stylesheet" type="text/css" href="../../css/normalize.css">
    <link rel="stylesheet" type="text/css" href="../../css/swiper-bundle.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <!-- MDB -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Marcellus&display=swap"
        rel="stylesheet">
    <link href="/e-web/user/css/tailwind-replacement.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            color: black;
            /* Thêm fallback font */
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            font-family: 'Times New Roman', Times, serif !important;
            /* Sử dụng font Times New Roman cho tiêu đề */
        }

        /* Layout styles */
        .flex {
            display: flex;
        }

        .flex-col {
            flex-direction: column;
        }

        .flex-1 {
            flex: 1 1 0%;
        }

        .grid {
            display: grid;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .gap-6 {
            gap: 1.5rem;
        }

        .gap-x-6 {
            column-gap: 1.5rem;
        }

        .gap-y-4 {
            row-gap: 1rem;
        }

        /* Responsive Grid */
        @media (min-width: 640px) {
            .sm\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        /* Spacing */
        .p-6 {
            padding: 1.5rem;
        }

        .p-3 {
            padding: 0.75rem;
        }

        .mb-6 {
            margin-bottom: 1.5rem;
        }

        .mb-3 {
            margin-bottom: 0.75rem;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mb-1 {
            margin-bottom: 0.25rem;
        }

        /* Typography */
        .text-2xl {
            font-size: 1.5rem;
        }

        .text-lg {
            font-size: 1.125rem;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .font-semibold {
            font-weight: 600;
        }

        .status-tabs-container {
            position: relative;
            border-bottom: 1px solid #e5e7eb;
            /* Đường kẻ xám mỏng ngang qua */
            margin-bottom: 24px;
            /* Khoảng cách với nội dung bên dưới */
        }

        .status-tab {
            padding: 12px 16px;
            cursor: pointer;
            font-weight: 500;
            color: #6b7280;
            /* Màu chữ mặc định xám */
            position: relative;
            transition: color 0.3s ease;
            white-space: nowrap;
            /* Ngăn không cho chữ xuống dòng */
        }

        .status-tab:hover:not(.active) {
            color: #374151;
            /* Màu chữ khi hover */
        }

        .status-tab.active {
            color: #000;
            /* Màu chữ khi active */
            font-weight: 600;
        }

        .status-tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            /* Đặt ở cuối tab, ngay trên đường border-bottom của container */
            left: 0;
            width: 100%;
            height: 2px;
            /* Độ dày của đường kẻ active */
            background-color: #000;
            /* Màu đen như hình */
        }

        /* Ẩn thanh cuộn ngang nếu nội dung tab quá dài */
        .status-tabs-wrapper {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .status-tabs-wrapper::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
        }

        .cancel-button {
        background-color: #ef4444; /* red-500 */
        color: #ffffff; /* white */
        transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
        
        /* Cập nhật hoặc thêm các thuộc tính này */
        border: none; /* Bỏ viền */
        border-radius: 9999px; /* Bo tròn hoàn toàn (hoặc dùng 50px nếu bạn muốn nó trông như pill) */
        /* Nếu bạn vẫn muốn dùng rounded-md từ Tailwind, thì không cần border-radius ở đây */
    }
    .cancel-button:hover {
        background-color: #dc2626; /* red-600 */
    }
    </style>
</head>

<body>
    <?php include('../../../navbar.php'); ?>
    <div class="flex min-h-screen py-8 px-4 sm:px-6 lg:px-8" style="background-color: #f1f1f0;">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/e-web/sidebar2.php'; ?>
        <div class="flex-1 bg-white p-6 rounded-lg shadow-md" style="margin: 20px 0;">
            <h3 class="text-2xl font-semibold mb-6 text-gray-800" style="border-bottom: 1px solid rgba(0,0,0,0.1); padding-bottom: 15px;">Quản lý đơn hàng</h3>
            <div class="status-tabs-container">
                <div class="flex space-x-6 status-tabs-wrapper">
                    <div class="status-tab active" data-status="All">Tất cả (<span class="order-count">0</span>)</div>
                    <div class="status-tab" data-status="Pending">Chờ xác nhận (<span class="order-count">0</span>)</div>
                    <div class="status-tab" data-status="PendingPaid">Đã thanh toán (<span class="order-count">0</span>)</div>
                    <div class="status-tab" data-status="Confirmed">Chờ lấy hàng (<span class="order-count">0</span>)</div>
                    <div class="status-tab" data-status="Shipping">Đang giao (<span class="order-count">0</span>)</div>
                    <div class="status-tab" data-status="Delivered">Đã giao (<span class="order-count">0</span>)</div>
                    <div class="status-tab" data-status="Cancelled">Đã hủy (<span class="order-count">0</span>)</div>
                    <div class="status-tab" data-status="Return">Trả hàng (<span class="order-count">0</span>)</div>
                </div>
            </div>
            <div id="order-list-content" class="text-center py-10">
                <img src="https://theciu.vn/assets/images/empty-product.svg" alt="No orders" class="mx-auto mb-6 w-64 h-auto">
                <p class="text-lg font-medium text-gray-700 mb-4">Bạn chưa có đơn hàng nào?</p>
                <button class="bg-gray-800 text-white py-2 px-6 rounded-full hover:bg-gray-700 transition-colors duration-300">Quay về trang chủ</button>
            </div>

            <div id="actual-order-list" class="hidden">
            </div>
        </div>
    </div>
    <?php include('../../../footer.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusTabs = document.querySelectorAll('.status-tab');
            const orderListContent = document.getElementById('order-list-content');
            const actualOrderList = document.getElementById('actual-order-list');
            const backToHomeButton = document.querySelector('#order-list-content button');

            // Hàm helper để định dạng tiền tệ
            function formatCurrency(amount) {
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(amount);
            }

            // Hàm helper để lấy class Tailwind cho badge trạng thái
            function getStatusBadgeClass(status) {
                switch (status) {
                    case 'Pending':
                        return 'bg-blue-100 text-blue-800';
                    case 'Confirmed':
                        return 'bg-yellow-100 text-yellow-800';
                    case 'Shipping':
                        return 'bg-purple-100 text-purple-800';
                    case 'Delivered':
                        return 'bg-green-100 text-green-800';
                    case 'Cancelled':
                        return 'bg-red-100 text-red-800';
                    case 'Return':
                        return 'bg-orange-100 text-orange-800';
                    default:
                        return 'bg-gray-100 text-gray-800';
                }
            }
            // Hàm helper để hiển thị tên trạng thái tiếng Việt cho paystatus
            function getDisplayPayStatus(paystatus) {
                switch (paystatus) {
                    case 'Pending':
                        return 'Chờ thanh toán';
                    case 'Paid':
                        return 'Đã thanh toán';
                    case 'Awaiting refund':
                        return 'Chờ hoàn tiền';
                    case 'Refunded':
                        return 'Đã hoàn tiền';
                    default:
                        return paystatus; // Trả về trạng thái gốc nếu không khớp
                }
            }
            // Hàm helper để hiển thị tên trạng thái tiếng Việt
            function getDisplayStatus(status) {
                switch (status) {
                    case 'Pending':
                        return 'Chờ xác nhận';
                    case 'Confirmed':
                        return 'Đã xác nhận';
                    case 'Shipping':
                        return 'Đang giao';
                    case 'Delivered':
                        return 'Đã giao';
                    case 'Cancelled':
                        return 'Đã hủy';
                    case 'Return':
                        return 'Đã hoàn trả';
                    default:
                        return status;
                }
            }

            // Thêm hàm mới để cập nhật số lượng đơn hàng cho mỗi tab
            async function updateOrderCounts() {
                try {
                    const statuses = ['All', 'Pending', 'PendingPaid', 'Confirmed', 'Shipping', 'Delivered', 'Cancelled', 'Return'];

                    for (const status of statuses) {
                        const url = `/e-web/user/page/ajax_handlers/fetch_order.php?status=${status}`;
                        const response = await fetch(url);
                        const data = await response.json();

                        if (data.success) {
                            const tab = document.querySelector(`.status-tab[data-status="${status}"]`);
                            if (tab) {
                                const countSpan = tab.querySelector('.order-count');
                                if (countSpan) {
                                    countSpan.textContent = data.orders.length;
                                }
                            }
                        }
                    }
                } catch (error) {
                    console.error('Error updating order counts:', error);
                }
            }

            // Gọi hàm cập nhật số lượng khi trang được load
            updateOrderCounts();

            // Event listener cho các tab trạng thái
            statusTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    statusTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    const selectedStatus = this.dataset.status;
                    fetchAndDisplayOrders(selectedStatus);
                });
            });

            // Event listener cho nút quay về trang chủ
            if (backToHomeButton) {
                backToHomeButton.addEventListener('click', function() {
                    window.location.href = '/e-web/user/index.php';
                });
            }

            // Hàm helper để xử lý đường dẫn thumbnail
            function processThumbnailPath(thumbnail) {
                // Nếu đường dẫn bắt đầu bằng 'admin/assets/images/', loại bỏ phần đó
                if (thumbnail.startsWith('admin/assets/images/')) {
                    thumbnail = thumbnail.substring('admin/assets/images/'.length);
                }
                // Trả về đường dẫn đầy đủ và đã được encode
                return '/e-web/admin/assets/images/' + encodeURIComponent(thumbnail);
            }

            // Hàm để lấy và hiển thị đơn hàng
            async function fetchAndDisplayOrders(status) {
                actualOrderList.innerHTML = '';
                orderListContent.classList.add('hidden');

                try {
                    let url = `/e-web/user/page/ajax_handlers/fetch_order.php?status=${status}`;

                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    console.log('Orders data:', data);

                    if (data.success && data.orders.length > 0) {
                        actualOrderList.classList.remove('hidden');

                        data.orders.forEach(order => {
                            let productsHtml = '';
                            let totalProductsInOrder = 0;
                            order.products.forEach(product => {
                                totalProductsInOrder += product.quantity;
                                const displayPrice = formatCurrency(product.item_price_at_order);
                                const originalPrice = formatCurrency(product.product_original_price);
                                const hasDiscount = product.discount_percentage > 0;

                                // Xử lý đường dẫn thumbnail
                                const thumbnailUrl = processThumbnailPath(product.thumbnail);

                                productsHtml += `
                                <div class="product-item flex items-center gap-4 mb-3">
                                    <div class="flex-shrink-0" style="width: 100px; height: 100px;">
                                        <img src="${thumbnailUrl}" 
                                             alt="${product.title ? htmlspecialchars(product.title) : ''}" 
                                             class="img-fluid rounded-3 w-full h-full object-cover" 
                                             style="width: 100px; height: 100px;">
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800 product-title mb-2">
                                            ${product.title ? htmlspecialchars(product.title) : ''} 
                                            ${product.color ? `(Phân loại: ${htmlspecialchars(product.color)}` : ''}${product.size ? (product.color ? ', ' : '(') + `Size ${htmlspecialchars(product.size)})` : (product.color ? ')' : '')}
                                        </p>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <span class="product-price">${displayPrice}</span>
                                            <span class="mx-2">x</span>
                                            <span class="product-quantity">${product.quantity}</span>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-gray-800 product-subtotal">${formatCurrency(product.item_price_at_order * product.quantity)}</p>
                                </div>
                            `;
                            });

                            const orderHtml = `
                            <div class="order-item-container bg-white p-4 rounded-lg shadow-sm mb-4 border border-gray-200">
                                <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-200">
                                    <span class="text-sm font-semibold text-gray-700">Đơn hàng #<span class="order-id">${order.oid}</span></span>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-gray-500 order-date">${new Date(order.create_at).toLocaleDateString('vi-VN')}</span>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full order-status ${getStatusBadgeClass(order.destatus)}">${getDisplayStatus(order.destatus)}</span>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">${getDisplayPayStatus(order.paystatus)}</span>
                                    </div>
                                </div>

                                <div class="order-products-list">
                                    ${productsHtml}
                                </div>

                                <div class="border-t border-gray-200 pt-3 mt-3">
                                    <div class="flex justify-between items-center text-sm mb-1">
                                        <span class="text-gray-600">Tổng tiền hàng (${totalProductsInOrder} sản phẩm):</span>
                                        <span class="text-gray-800 font-medium order-subtotal-price">${formatCurrency(order.original_order_subtotal)}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm mb-1">
                                        <span class="text-gray-600">Phí vận chuyển:</span>
                                        <span class="text-gray-800 font-medium order-shipping-fee">${formatCurrency(order.shipping_fee)}</span>
                                    </div>
                                    ${order.voucher_discount > 0 ? `
                                    <div class="flex justify-between items-center text-sm mb-3">
                                        <span class="text-gray-600">Voucher từ Shop:</span>
                                        <span class="text-green-600 font-medium order-shop-voucher">-${formatCurrency(order.voucher_discount)}</span>
                                    </div>
                                    ` : ''}
                                    <div class="flex justify-between items-center text-base font-bold">
                                        <span class="text-gray-800">Thành tiền:</span>
                                        <span class="text-red-600 order-final-total">${formatCurrency(order.totalfinal)}</span>
                                    </div>
                                </div>
                                ${order.paymethod === 'COD' && order.paystatus === 'Pending' ? `
                                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-2 rounded-md mt-4 text-sm flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span class="payment-note">Vui lòng thanh toán <span class="order-final-total-note font-semibold">${formatCurrency(order.totalfinal)}</span> khi nhận hàng.</span>
                                    </div>
                                ` : ''}
                                <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-200">
                                    <span class="text-sm text-gray-600">Phương thức Thanh toán</span>
                                    <span class="text-gray-800 font-medium order-payment-method">${order.paymethod}</span>
                                </div>
                                ${order.destatus === 'Pending' ? `
                                <div class="flex justify-end mt-3 pt-3 border-t border-gray-200">
                                    <button class="cancel-order-btn cancel-button px-4 py-2 rounded-md text-sm" data-order-id="${order.oid}">
        Hủy đơn hàng
    </button>
                                </div>
                                ` : ''}
                            </div>
                        `;
                            actualOrderList.insertAdjacentHTML('beforeend', orderHtml);
                        });

                    } else {
                        actualOrderList.classList.add('hidden');
                        orderListContent.classList.remove('hidden');
                    }

                } catch (error) {
                    console.error('Error fetching orders:', error);
                    actualOrderList.classList.add('hidden');
                    orderListContent.classList.remove('hidden');
                }
            }

            // Add event delegation for cancel order buttons
            actualOrderList.addEventListener('click', async function(e) {
                if (e.target.matches('.cancel-order-btn')) {
                    const orderId = e.target.dataset.orderId;
                    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')) {
                        try {
                            const response = await fetch('/e-web/user/page/ajax_handlers/cancel_order.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    order_id: orderId
                                })
                            });

                            const data = await response.json();
                            if (data.success) {
                                alert('Đơn hàng đã được hủy thành công!');
                                // Refresh the orders list
                                const activeTab = document.querySelector('.status-tab.active');
                                if (activeTab) {
                                    fetchAndDisplayOrders(activeTab.dataset.status);
                                }
                                // Update order counts
                                updateOrderCounts();
                            } else {
                                alert(data.message || 'Có lỗi xảy ra khi hủy đơn hàng.');
                            }
                        } catch (error) {
                            console.error('Error cancelling order:', error);
                            alert('Có lỗi xảy ra khi hủy đơn hàng.');
                        }
                    }
                }
            });

            // Tải đơn hàng ban đầu khi trang được load (mặc định là 'All')
            fetchAndDisplayOrders('All');
        });

        // Thêm hàm htmlspecialchars vào đầu file JavaScript
        function htmlspecialchars(str) {
            if (typeof str !== 'string') return '';
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    </script>
</body>

</html>