<?php
require_once __DIR__ . '/../config/view_functions.php';

$route = $_GET['route'] ?? '/admin';
if (!is_string($route)) {
    http_response_code(400);
    exit('Route không hợp lệ');
}

// Trang quản trị mặc định mở module sản phẩm MVC thật.
if ($route === '/admin' || $route === '/admin/') {
    header('Location: /admin/index.php?route=/admin/products', true, 302);
    exit;
}

// Chỉ những route đã có Controller xử lý dữ liệu mới được hoạt động.
if (preg_match('~^/admin/products(?:$|/(?:create|store|edit/[1-9][0-9]*|update/[1-9][0-9]*|[1-9][0-9]*/variants(?:/store)?)$)~', $route)) {
    require_once __DIR__ . '/../config/pdo.php';
    require_once __DIR__ . '/controller/ProductController.php';
    $controller = new ProductController($pdo);

    if ($route === '/admin/products') $controller->index();
    elseif ($route === '/admin/products/create') $controller->create();
    elseif ($route === '/admin/products/store') $controller->store();
    elseif (preg_match('~^/admin/products/edit/([1-9][0-9]*)$~', $route, $m)) $controller->edit((int)$m[1]);
    elseif (preg_match('~^/admin/products/update/([1-9][0-9]*)$~', $route, $m)) $controller->update((int)$m[1]);
    elseif (preg_match('~^/admin/products/([1-9][0-9]*)/variants(?:/store)?$~', $route, $m)) {
        require_once __DIR__ . '/controller/ProductVariantController.php';
        (new ProductVariantController($pdo))->{str_ends_with($route, '/store') ? 'store' : 'index'}((int)$m[1]);
    }
    exit;
}

http_response_code(501);
header('Content-Type: text/html; charset=utf-8');
echo '<!doctype html><html lang="vi"><meta charset="utf-8"><title>Chưa kết nối MVC</title>';
echo '<div style="font:16px Arial;max-width:650px;margin:80px auto"><h2>Chức năng chưa kết nối dữ liệu thật</h2>';
echo '<p>Trang này mới có giao diện tham khảo, chưa có Controller và Model xử lý. Đã tắt chế độ demo.</p>';
echo '<a href="/admin/index.php?route=/admin/products">Quay về quản lý sản phẩm thật</a></div></html>';
