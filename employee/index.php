<?php
// Employee chưa có nghiệp vụ thực. Không gọi renderPreview() của demo.
http_response_code(501);
header('Content-Type: text/html; charset=utf-8');
echo '<!doctype html><html lang="vi"><meta charset="utf-8"><title>Nhân viên chưa hoàn thiện</title>';
echo '<div style="font:16px Arial;max-width:650px;margin:80px auto"><h2>Phần Nhân viên chưa kết nối MVC thật</h2>';
echo '<p>Đã ngừng sử dụng Router demo. Cần kết nối Controller, Model và dữ liệu thật trước khi sử dụng.</p>';
echo '<a href="/admin/index.php?route=/admin/products">Mở quản lý sản phẩm</a></div></html>';
