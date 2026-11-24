<?php
// Client chưa có Controller xử lý nghiệp vụ: không hiển thị dữ liệu demo.
// Khi mở dự án ở localhost:3000, chuyển đến module MVC đã hoạt động.
$route = $_GET['route'] ?? '/';
if (!is_string($route)) { http_response_code(400); exit('Route không hợp lệ'); }
if ($route === '/') {
    header('Location: /admin/index.php?route=/admin/products', true, 302);
    exit;
}
http_response_code(501);
header('Content-Type: text/html; charset=utf-8');
echo '<!doctype html><html lang="vi"><meta charset="utf-8"><title>Client chưa hoàn thiện</title>';
echo '<div style="font:16px Arial;max-width:650px;margin:80px auto"><h2>Client chưa được kết nối MVC thật</h2>';
echo '<p>Không còn gọi giao diện demo. Các Controller của Client trong bản gốc hiện vẫn đang là file khung.</p>';
echo '<a href="/admin/index.php?route=/admin/products">Mở quản lý sản phẩm</a></div></html>';
