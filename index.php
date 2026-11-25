<?php

use App\Helpers\SecurityHelper;
use App\Helpers\SessionHelper;

require_once __DIR__ . '/admin/helpers/SessionHelper.php';
require_once __DIR__ . '/admin/helpers/SecurityHelper.php';

SessionHelper::start();

if (!defined('APP_NAME')) {
    define('APP_NAME', 'DTD STORE');
}

$route = $_GET['route'] ?? '/';
if (!is_string($route) || strlen($route) > 200) {
    http_response_code(400);
    exit('Route không hợp lệ');
}

$knownClientRoutes = [
    '/',
    '/products',
    '/cart',
    '/checkout',
    '/orders',
    '/login',
    '/register',
    '/logout',
    '/account',
    '/account/edit',
];

$isKnownClientRoute = in_array($route, $knownClientRoutes, true)
    || preg_match('~^/(?:product|category)/[a-zA-Z0-9_-]+$~', $route) === 1
    || preg_match('~^/orders/[1-9][0-9]*(?:/(?:qr|success))?$~', $route) === 1;

$baseUrl = SecurityHelper::baseUrl();
$adminProductsUrl = SecurityHelper::url('/admin/products');
$adminUrl = SecurityHelper::url('/admin');
$employeeUrl = SecurityHelper::url('/employee');
$demoListUrl = $baseUrl . '/danh-sach-giao-dien.php';

if ($isKnownClientRoute) {
    http_response_code(501);
    $title = 'Chức năng Client đang phát triển | ' . APP_NAME;
    $heading = 'Phần Client đang phát triển';
    $message = 'Route <code>' . SecurityHelper::e($route) . '</code> đã được khai báo trong Router MVC chính thức, nhưng Controller và Model xử lý nghiệp vụ Client hiện chưa hoàn thiện.';
} else {
    http_response_code(404);
    $title = '404 - Không tìm thấy trang | ' . APP_NAME;
    $heading = '404 - Không tìm thấy trang';
    $message = 'Đường dẫn <code>' . SecurityHelper::e($route) . '</code> không tồn tại trong hệ thống.';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SecurityHelper::e($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5 text-center">
                    <div class="mb-3">
                        <span class="badge <?= $isKnownClientRoute ? 'bg-warning text-dark' : 'bg-danger' ?> fs-6 px-3 py-2">
                            <?= $isKnownClientRoute ? 'MVC Chính thức · Đang phát triển' : 'HTTP 404' ?>
                        </span>
                    </div>
                    <h1 class="h3 fw-bold mb-3"><?= SecurityHelper::e($heading) ?></h1>
                    <p class="text-muted mb-4"><?= $message ?></p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <a href="<?= SecurityHelper::e($adminProductsUrl) ?>" class="btn btn-danger">
                            <i class="bi bi-phone me-1"></i>Quản lý sản phẩm (MVC thật)
                        </a>
                        <a href="<?= SecurityHelper::e($adminUrl) ?>" class="btn btn-outline-dark">
                            <i class="bi bi-speedometer2 me-1"></i>Khu vực Admin
                        </a>
                        <a href="<?= SecurityHelper::e($employeeUrl) ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-person-badge me-1"></i>Khu vực Nhân viên
                        </a>
                        <a href="<?= SecurityHelper::e($demoListUrl) ?>" class="btn btn-outline-primary">
                            <i class="bi bi-collection me-1"></i>Xem giao diện Demo riêng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
