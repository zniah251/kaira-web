<?php
session_start();
ob_start();
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
require '../../../connect.php';

// Thêm
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = basename($_FILES['image']['name']);
        $target = __DIR__ . '/../../assets/images/blog/' . $image; // SỬA LẠI ĐƯỜNG DẪN NÀY
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $stmt = $conn->prepare("INSERT INTO blog (title, content, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $image);
    $stmt->execute();
}

// Sửa
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = basename($_FILES['image']['name']);
        $target = __DIR__ . '/../../assets/images/blog/' . $image; // GIỮ NGUYÊN NHƯ HIỆN TẠI
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $stmt = $conn->prepare("UPDATE blog SET title=?, content=?, image=? WHERE bid=?");
        $stmt->bind_param("sssi", $title, $content, $image, $id);
    } else {
        $stmt = $conn->prepare("UPDATE blog SET title=?, content=? WHERE bid=?");
        $stmt->bind_param("ssi", $title, $content, $id);
    }
    $stmt->execute();
}

// Xóa
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM blog WHERE bid=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: blog.php");
    exit();
}

// Lấy dữ liệu sửa
$editMode = false;
if (isset($_GET['edit'])) {
    $editMode = true;
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM blog WHERE bid=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
}
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
    <!-- Layout styles -->

    <link rel="stylesheet" href="/e-web/admin/template/assets/css/style.css">

    <!-- End layout styles -->
    <link rel="shortcut icon" href="../../../admin/template/assets/images/favicon.png" />
    <!-- Sử dụng liên kết CDN mới nhất của Font Awesome -->

    <!-- Thêm vào trước thẻ </body> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.7.0/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            /* Thêm fallback font */
        }

        /* Đặt sau Bootstrap, hoặc trong file riêng cuối trang */
        .btn-link.custom-purple {
            color: #7c3aed !important;
            /* Màu tím */
            background: none !important;
            box-shadow: none !important;
            border: none !important;
            text-decoration: none !important;
        }

        .btn-link.custom-gray {
            color: #9ca3af !important;
            /* Màu xám */
            background: none !important;
            box-shadow: none !important;
            border: none !important;
            text-decoration: none !important;
        }

        /* Đảm bảo form không bị block */
        .d-flex form {
            display: inline-block;
            margin: 0;
        }
        .card-title {
  font-family: 'Jost', sans-serif;
  color: #000;
}

.card-text {
  color: #333;
}

.card:hover {
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  transition: all 0.3s ease-in-out;
}
.card-title,
.card-body h4,
.table th,
.table td,
label,
h6.text-muted,
.btn {
    color: #fff !important;
}
input,
textarea {
  color: #fff !important;
  background-color: #2c2c38 !important; /* hoặc màu nền bạn thích */
}

input::placeholder,
textarea::placeholder {
  color: #aaa !important;
}


    </style>
</head>

<body>
    <div class="container-scroller">
        <?php include('../../template/sidebar.php'); ?>
        <div class="container-fluid page-body-wrapper">
            <?php include('../../template/navbar.php'); ?>
            <div class="main-panel">
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <!-- Card thống kê tổng số bài blog -->
<div class="col-xl-3 col-sm-6 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-9">
                    <div class="d-flex align-items-center align-self-start">
                        <?php
                        // Đếm tổng số bài blog đã đăng
                        $blog_count_sql = "SELECT COUNT(*) as total_blogs FROM blog";
                        $blog_result = $conn->query($blog_count_sql);
                        $blog_row = $blog_result->fetch_assoc();
                        ?>
                        <h3 class="mb-0"><?php echo $blog_row['total_blogs']; ?></h3>
                    </div>
                </div>
                <div class="col-3">
                    <div class="icon icon-box-success">
                        <span class="mdi mdi-post-outline icon-item"></span>
                        <!-- Có thể thay đổi icon khác tùy ý như: mdi-note-text-outline, mdi-newspaper -->
                    </div>
                </div>
            </div>
            <h6 class="text-muted font-weight-normal">Tổng số bài blog</h6>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title"><?php echo $editMode ? 'Sửa bài viết' : 'Thêm bài viết mới'; ?></h4>
        <form class="forms-sample" method="POST" enctype="multipart/form-data">
          <?php if ($editMode): ?>
            <input type="hidden" name="id" value="<?= $post['bid'] ?>">
          <?php endif; ?>

          <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Tiêu đề" value="<?= $editMode ? $post['title'] : '' ?>" required>
          </div>

          <div class="form-group">
            <label for="content">Nội dung</label>
            <textarea class="form-control" id="content" name="content" rows="10"><?= $editMode ? $post['content'] : '' ?></textarea>
              <script>
                CKEDITOR.replace('content');
              </script>
          </div>
          <div class="form-group">
            <label>Hình ảnh</label>
            <input type="file" name="image" class="form-control" <?= $editMode ? '' : 'required' ?>>
            <?php if ($editMode && $post['image']): ?>
              <p>Hiện tại: <img src="../../assets/images/blog/<?= $post['image'] ?>" width="100"></p>
            <?php endif; ?>
          </div>

          <button type="submit" name="<?= $editMode ? 'edit' : 'add' ?>" class="btn btn-primary me-2"><?= $editMode ? 'Cập nhật' : 'Thêm bài viết' ?></button>
        </form>
      </div>
    </div>
  </div>
</div>
<form method="GET" class="mb-3 d-flex" style="max-width: 400px;">
    <input type="text" name="search" class="form-control me-2" placeholder="Tìm theo tiêu đề" 
        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
    <button type="submit" class="btn btn-primary">Tìm</button>
</form>

<div class="row mt-4">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Danh sách bài viết</h4>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th> ID </th>
                <th> Tiêu đề </th>
                <th> Ngày tạo </th>
                <th> Hành động </th>
              </tr>
            </thead>
            <tbody>
<?php
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $search_escaped = $conn->real_escape_string($search);
    $sql = "SELECT * FROM blog WHERE title LIKE '%$search_escaped%' ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM blog ORDER BY created_at DESC";
}
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>{$row['bid']}</td>";
    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
    echo "<td>" . date('d/m/Y', strtotime($row['created_at'])) . "</td>";
    echo "<td>
            <a href='blog.php?edit={$row['bid']}' class='btn btn-warning btn-sm'>Sửa</a>
            <a href='blog.php?delete={$row['bid']}' onclick='return confirm(\"Xóa bài viết này?\")' class='btn btn-danger btn-sm'>Xóa</a>
          </td>";
    echo "</tr>";
}
?>
</tbody>

          </table>
        </div>
      </div>
    </div>
  </div>
</div>

         
    
  </body>
  <script src="../../template/assets/vendors/js/vendor.bundle.base.js"></script>

</html>