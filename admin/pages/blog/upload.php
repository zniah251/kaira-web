<?php
$targetDir = __DIR__ . '/../../../blog/';
$targetUrl = '/e-web/blog/'; // đường dẫn để truy cập ảnh

if (isset($_FILES['upload'])) {
    $file = $_FILES['upload'];
    $fileName = basename($file['name']);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        $funcNum = $_GET['CKEditorFuncNum'];
        $url = $targetUrl . $fileName;
        $message = 'Tải ảnh thành công!';
        echo "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
    } else {
        echo "Lỗi khi upload ảnh.";
    }
}
