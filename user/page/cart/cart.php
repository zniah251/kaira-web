<?php
session_start();
require_once "../../../connect.php";

// ── Kiểm tra đăng nhập ──────────────────────────────────────────────────────
$uid = isset($_SESSION['uid']) ? (int)$_SESSION['uid'] : 0;

// Xử lý xóa sản phẩm (AJAX POST với remove_key)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_key'])) {
    $key   = trim($_POST['remove_key']);
    $parts = explode('_', $key, 3);
    if ($uid > 0 && count($parts) === 3) {
        $pid   = (int)$parts[0];
        $size  = $parts[1] === 'nosize'  ? '' : $parts[1];
        $color = $parts[2] === 'nocolor' ? '' : $parts[2];

        $stmt = $conn->prepare(
            "DELETE FROM `cart` WHERE uid = ? AND pid = ? AND size = ? AND color = ?"
        );
        if ($stmt) {
            $stmt->bind_param("iiss", $uid, $pid, $size, $color);
            $stmt->execute();
            $stmt->close();
        }
    }
    // Không redirect, vẫn render trang (AJAX gọi xong tự remove row trên DOM)
}

// ── Load giỏ hàng từ DB ─────────────────────────────────────────────────────
$cart_items = [];
if ($uid > 0) {
    $stmt = $conn->prepare(
        "SELECT c.caid, c.pid, c.quantity, c.size, c.color,
                p.title, p.price, p.thumbnail
         FROM `cart` c
         JOIN `product` p ON p.pid = c.pid
         WHERE c.uid = ?
         ORDER BY c.create_at DESC"
    );
    if ($stmt) {
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            // Tạo key giống format cũ để JS (data-key, updateQuantityOnServer) vẫn hoạt động
            $sz  = $row['size']  !== '' ? $row['size']  : 'nosize';
            $clr = $row['color'] !== '' ? $row['color'] : 'nocolor';
            $key = $row['pid'] . '_' . $sz . '_' . $clr;

            $cart_items[$key] = [
                'pid'       => $row['pid'],
                'title'     => $row['title'],
                'price'     => (float)$row['price'],
                'thumbnail' => '/e-web/admin/assets/images/' . rawurlencode($row['thumbnail']),
                'quantity'  => (int)$row['quantity'],
                'size'      => $row['size'],
                'color'     => $row['color'],
            ];
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <title>Kaira Shopping Cart</title>
  <!-- MDB icon -->
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

  
  <style>
   body {
            font-family: 'Times New Roman', serif;
            /* Thêm fallback font */
        }
        h1, h2, h3, h4, h5,h6 {
            font-family: 'Times New Roman', Times, serif !important;
            color: black;
        }

    .card-registration .select-input.form-control[readonly]:not([disabled]) {
      font-size: 1rem;
      line-height: 2.15;
      padding-left: .75em;
      padding-right: .75em;
    }

    .card-registration .select-arrow {
      top: 13px;
    }

    .bg-grey {
      background-color: #eae8e8;
    }

    @media (min-width: 992px) {
      .card-registration-2 .bg-grey {
        border-top-right-radius: 16px;
        border-bottom-right-radius: 16px;
      }
    }

    @media (max-width: 991px) {
      .card-registration-2 .bg-grey {
        border-bottom-left-radius: 16px;
        border-bottom-right-radius: 16px;
      }
    }

    .tableproduct {
      width: 100% !important;
    }

    .tableproduct th,
    .tableproduct td {
      padding-left: 12px;
      padding-right: 12px;
      color: black !important;
    }

    .tableproduct thead th {
      border-bottom: 2px solid #dee2e6 !important;
      color: black !important;
    }

    .tableproduct tfoot th {
      border-top: 2px solid #dee2e6 !important;
      color: black !important;
    }

    .tableproduct tbody tr:first-child td {
      padding-top: 18px;
    }

    .tableproduct tbody tr:last-child td {
      padding-bottom: 18px;
    }
  </style>
</head>

<body>
  <?php include('../../../navbar.php'); ?>
  <!-- Start your project here-->
  <section class="h-100 h-custom" style="background-color: #F1F1F0;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12">
          <div class="card card-registration card-registration-2" style="border-radius: 15px;">
            <div class="card-body p-0">
              <div class="row g-0">
                <div class="col-lg-12">
                  <div class="p-5">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                      <h1 class="fw-bold mb-0 text-black">Shopping Cart</h1>
                      <h6 class="mb-0 text-muted">
                        <?php echo count($cart_items); ?> items
                      </h6>
                    </div>

                    <?php if ($uid <= 0): ?>
                      <div class="alert alert-warning text-center">
                        Bạn cần <a href="/e-web/user/page/sign-in/login2.php?redirect=<?= urlencode('/e-web/user/page/cart/cart.php') ?>">đăng nhập</a> để xem giỏ hàng.
                      </div>
                    <?php else: ?>

                    <div class="p-0">
                      <div class="table-responsive">
                        <table class="tableproduct w-100">
                          <thead>
                            <tr>
                              <th style="width: 40px;" class="text-center">
                                <input type="checkbox" id="select-all">
                              </th>
                              <th scope="col" class="col-2 fs-6">Product</th>
                              <th scope="col" class="col-2 fs-6">Name</th>
                              <th scope="col" class="col-1 fs-6">Size</th>
                              <th style="width: 100px;" class="text-center">Color</th>
                              <th style="width: 160px;" class="text-center">Quantity</th>
                              <th style="width: 120px;" class="text-center">Price</th>
                              <th scope="col" class="col-1 text-end fs-6"></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $total = 0;
                            $index = 0;
                            if (!empty($cart_items)) {
                              foreach ($cart_items as $key => $item) {
                                $item_total = $item['price'] * $item['quantity'];
                                $total += $item_total;
                            ?>
                                <tr>
                                  <td class="text-center">
                                    <input type="checkbox" class="product-checkbox">
                                  </td>
                                  <td>
                                    <img src="<?php echo htmlspecialchars($item['thumbnail']); ?>"
                                      class="img-fluid rounded-3" alt="<?php echo htmlspecialchars($item['title']); ?>" style="width: 100px;">
                                  </td>
                                  <td>
                                    <h6 class="text-black mb-0"><?php echo htmlspecialchars($item['title']); ?></h6>
                                  </td>
                                  <td>
                                    <h6 class="text-black mb-0"><?php echo htmlspecialchars($item['size']); ?></h6>
                                  </td>
                                  <td class="text-center"><?php echo htmlspecialchars($item['color']); ?></td>
                                  <td class="text-center">
                                    <div class="d-inline-flex align-items-center">
                                      <button class="btn btn-link px-1 btn-qty-minus" type="button">
                                        <i class="fas fa-minus"></i>
                                      </button>
                                      <input min="1" name="quantity" value="<?php echo $item['quantity']; ?>" type="number"
                                        class="form-control form-control-sm mx-1 quantity-input" style="width: 50px;" 
                                        data-index="<?php echo $index; ?>"
                                        data-price="<?php echo $item['price']; ?>" 
                                        data-key="<?php echo $key; ?>" />
                                      <button class="btn btn-link px-1 btn-qty-plus" type="button">
                                        <i class="fas fa-plus"></i>
                                      </button>
                                    </div>
                                  </td>
                                  <td class="text-center">
                                    <h6 class="mb-0 item-total" id="item-total-<?php echo $index; ?>">
                                      <span id="item-total-value-<?php echo $index; ?>" data-original-price="<?php echo $item['price']; ?>">
                                        <?php echo number_format($item['quantity'] * $item['price'], 0, ',', '.'); ?>
                                      </span>
                                      <span style="font-size: 20px; font-weight: 500; vertical-align: middle;">₫</span>
                                    </h6>
                                  </td>
                                  <td class="text-end">
                                    <form method="post" class="remove-item-form" data-key="<?php echo htmlspecialchars($key); ?>" style="display:inline;">
                                      <button type="button" class="btn btn-link text-muted p-0 remove-btn">
                                        <i class="fas fa-times"></i>
                                      </button>
                                    </form>
                                  </td>
                                </tr>
                            <?php
                                $index++;
                              }
                            } else {
                              echo '<tr><td colspan="8" class="text-center py-4">Your cart is empty.</td></tr>';
                            }
                            ?>
                          </tbody>
                          <tfoot>
                            <tr>
                              <th colspan="6" class="text-end" style="font-weight: bold; font-size: 1.5rem;">Total price</th>
                              <th class="text-center" style="font-weight: bold; font-size: 1.5rem;">
                                <span id="cart-total"><?php echo number_format($total, 0, ',', '.'); ?></span>
                                <span style="font-size: 2rem; font-weight: bold; vertical-align: middle;">₫</span>
                              </th>
                              <th></th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>

                    <?php endif; ?>

                    <div class="d-flex align-items-center justify-content-between pt-5">
                      <a href="/e-web/user/index.php" class="text-body">
                        <i class="fas fa-long-arrow-alt-left me-2"></i>Back to shop
                      </a>
                      <?php if ($uid > 0): ?>
                      <!-- Form ẩn để POST các sản phẩm được chọn sang checkout -->
                      <form method="POST" action="../checkout/checkout.php" id="order-form"></form>
                      <button type="button" class="btn btn-dark" id="order-button" style="min-width: 120px;">Order</button>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </section>
  <?php include('../../../footer.php'); ?>

  <!-- End your project here-->

  <!-- MDB -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <script>
document.addEventListener('DOMContentLoaded', function () {
  const orderBtn = document.getElementById('order-button');
  if (!orderBtn) return;
  orderBtn.addEventListener('click', function (e) {
    const selectedCheckboxes = document.querySelectorAll('.product-checkbox:checked');
    if (selectedCheckboxes.length === 0) {
      alert("🛒 Bạn chưa chọn sản phẩm nào trong giỏ hàng!");
      return;
    }

    const form = document.getElementById('order-form');
    // Xóa hidden inputs cũ nếu có
    form.querySelectorAll('input[type=hidden]').forEach(function(el) { el.remove(); });

    selectedCheckboxes.forEach(function(checkbox) {
      const row = checkbox.closest('tr');
      const input = row.querySelector('.quantity-input');
      const key = input.dataset.key;
      const qty = input.value;

      const hKey = document.createElement('input');
      hKey.type = 'hidden';
      hKey.name = 'selected_keys[]';
      hKey.value = key;
      form.appendChild(hKey);

      const hQty = document.createElement('input');
      hQty.type = 'hidden';
      hQty.name = 'selected_qty[' + key + ']';
      hQty.value = qty;
      form.appendChild(hQty);
    });

    form.submit();
  });
});
</script>

</body>

<script>
function updateQuantityOnServer(key, quantity) {
  fetch('./update_cart_quantity.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `key=${encodeURIComponent(key)}&quantity=${encodeURIComponent(quantity)}`
  }).then(res => res.json())
    .then(data => {
      if (!data.success) {
        alert("Cập nhật thất bại: " + (data.error || ""));
      }
    });
}
</script>

<!-- Script xóa sản phẩm -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.remove-item-form .remove-btn').forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        if (!confirm('Remove this item from cart?')) return;
        const form = btn.closest('form');
        const key = form.getAttribute('data-key');
        fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'remove_key=' + encodeURIComponent(key)
          })
          .then(res => res.text())
          .then(() => {
            form.closest('tr').remove();
            if (typeof updateTotals === 'function') updateTotals();
          });
      });
    });
  });
</script>

<!-- Script cập nhật tổng tiền & số lượng -->
<script>
  function updateTotals() {
    let total = 0;
    document.querySelectorAll('.product-checkbox').forEach(function(checkbox) {
      const row   = checkbox.closest('tr');
      const input = row.querySelector('.quantity-input');
      const qty   = parseInt(input.value, 10);
      const index = input.dataset.index;

      let priceString = input.dataset.price.replace(/\./g, '').replace('đ', '').trim();
      const originalPrice = parseFloat(priceString);

      if (isNaN(originalPrice)) {
        console.error("Giá không hợp lệ cho index:", index);
      }

      const itemTotal = qty * originalPrice;
      const itemTotalElem = document.getElementById('item-total-value-' + index);
      if (itemTotalElem) {
        itemTotalElem.innerHTML = itemTotal.toLocaleString('vi-VN');
      }

      if (checkbox.checked) {
        total += itemTotal;
      }
    });
    document.getElementById('cart-total').innerText = total.toLocaleString('vi-VN');
  }

  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-qty-minus').forEach(function(btn) {
      btn.addEventListener('click', function() {
        const input = this.parentNode.querySelector('.quantity-input');
        if (parseInt(input.value, 10) > 1) {
          input.value = parseInt(input.value, 10) - 1;
          updateTotals();
          updateQuantityOnServer(input.dataset.key, input.value);
        }
      });
    });

    document.querySelectorAll('.btn-qty-plus').forEach(function(btn) {
      btn.addEventListener('click', function() {
        const input = this.parentNode.querySelector('.quantity-input');
        input.value = parseInt(input.value, 10) + 1;
        updateTotals();
        updateQuantityOnServer(input.dataset.key, input.value);
      });
    });

    document.querySelectorAll('.quantity-input').forEach(function(inputElement) {
      inputElement.addEventListener('input', function() {
        let enteredQty = parseInt(this.value, 10);
        if (isNaN(enteredQty) || enteredQty < 1) {
          this.value = NaN;
        }
        updateTotals();
      });
      inputElement.addEventListener('keypress', function(e) {
        if (e.which === 13) {
          this.blur();
          e.preventDefault();
        }
      });
    });

    document.querySelectorAll('.product-checkbox').forEach(function(checkbox) {
      checkbox.addEventListener('change', updateTotals);
    });

    const selectAll = document.getElementById('select-all');
    if (selectAll) {
      selectAll.addEventListener('change', function() {
        document.querySelectorAll('.product-checkbox').forEach(function(checkbox) {
          checkbox.checked = selectAll.checked;
        });
        updateTotals();
      });
    }

    updateTotals();
  });
</script>
</html>