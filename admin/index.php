<?php

// Lấy route hiện tại
$route = $_GET['route'] ?? '/admin';

if (!is_string($route)) {
    http_response_code(400);
    exit('Route không hợp lệ');
}

// Xử lý riêng trang sản phẩm bằng MVC thật
if ($route === '/admin/products') {

    require_once __DIR__ . '/../config/pdo.php';

    require_once __DIR__
        . '/helpers/SessionHelper.php';

    require_once __DIR__
        . '/helpers/SecurityHelper.php';

    require_once __DIR__
        . '/controller/ProductController.php';

    if (!defined('APP_NAME')) {
        define('APP_NAME', 'DTD STORE');
    }

    $controller = new ProductController($pdo);

    $controller->index();

    exit;
}

// Các route chưa chuyển sang MVC:
// Tạm giữ cơ chế cũ để không làm hỏng trang khác.
$demoRoute = $route;
$GLOBALS['prefix'] = '../';

require __DIR__ . '/../demo/render.php';
