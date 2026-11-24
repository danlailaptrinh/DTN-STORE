<?php
// Không kết nối database thật. Mọi dữ liệu ở đây chỉ là dữ liệu mẫu.
$demoRoute = $_GET['route'] ?? '/';
if (!is_string($demoRoute) || strlen($demoRoute)>200) {
    http_response_code(400);
    exit('Đường dẫn không hợp lệ');
}
$GLOBALS['prefix'] = '/demo/';
require __DIR__ . '/render.php';
