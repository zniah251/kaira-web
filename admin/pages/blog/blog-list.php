<?php include '../../../connect.php'; ?>
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Danh sách bài viết</h4>
        <a href="blog-add.php" class="btn btn-success mb-3">+ Thêm bài viết</a>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $result = mysqli_query($conn, "SELECT * FROM blog ORDER BY bid DESC");
              while($row = mysqli_fetch_assoc($result)) {
              ?>
              <tr>
                <td><?= $row['bid'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= $row['created_at'] ?></td>
                <td> 
                <a href="blog-edit.php?id=<?= $row['bid'] ?>">Sửa</a> |
                <a href="blog-delete.php?id=<?= $row['bid'] ?>" onclick="return confirm('Bạn có chắc muốn xóa bài viết này không?')">Xóa</a>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
