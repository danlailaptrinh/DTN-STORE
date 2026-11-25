<?php
// Router riêng cho giao diện demo. Không kết nối database thật.
$demoRoute = $_GET['route'] ?? '/';
if (!is_string($demoRoute) || strlen($demoRoute) > 200) {
    http_response_code(400);
    exit('Đường dẫn không hợp lệ');
}
require __DIR__ . '/render.php';
