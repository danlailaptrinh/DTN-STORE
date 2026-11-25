<?php

use App\Helpers\SecurityHelper;
use App\Helpers\SessionHelper;

require_once __DIR__ . '/helpers/SessionHelper.php';
require_once __DIR__ . '/helpers/SecurityHelper.php';

SessionHelper::start();

if (!defined('APP_NAME')) {
    define('APP_NAME', 'DTD STORE');
}

$route = $_GET['route'] ?? '/admin';

if (!is_string($route) || strlen($route) > 200) {
    http_response_code(400);
    exit('Route không hợp lệ');
}

// 1. Xử lý đăng xuất
if ($route === '/logout') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!SecurityHelper::verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            http_response_code(403);
            exit('CSRF không hợp lệ');
        }
        unset($_SESSION['user']);
        session_destroy();
    }
    header('Location: ' . SecurityHelper::url('/'), true, 302);
    exit;
}

// 2. Các route MVC chính thức đã kết nối Controller & Model thật (MySQL)
if (preg_match('~^/admin/products(?:$|/(?:create|store|edit/[1-9][0-9]*|update/[1-9][0-9]*|[1-9][0-9]*/(?:variants(?:/store)?|images(?:/(?:upload|primary|delete))?)))$~', $route)) {
    require_once __DIR__ . '/../config/pdo.php';
    require_once __DIR__ . '/controller/ProductController.php';
    $controller = new ProductController($pdo);

    if ($route === '/admin/products') {
        $controller->index();
    } elseif ($route === '/admin/products/create') {
        $controller->create();
    } elseif ($route === '/admin/products/store') {
        $controller->store();
    } elseif (preg_match('~^/admin/products/edit/([1-9][0-9]*)$~', $route, $m)) {
        $controller->edit((int) $m[1]);
    } elseif (preg_match('~^/admin/products/update/([1-9][0-9]*)$~', $route, $m)) {
        $controller->update((int) $m[1]);
    } elseif (preg_match('~^/admin/products/([1-9][0-9]*)/images(?:/(upload|primary|delete))?$~', $route, $m)) {
        $action = $m[2] ?? '';
        if ($action === 'upload') $controller->uploadImages((int) $m[1]);
        elseif ($action === 'primary') $controller->setPrimaryImage((int) $m[1]);
        elseif ($action === 'delete') $controller->deleteImage((int) $m[1]);
        else $controller->images((int) $m[1]);
    } elseif (preg_match('~^/admin/products/([1-9][0-9]*)/variants(?:/store)?$~', $route, $m)) {
        require_once __DIR__ . '/controller/ProductVariantController.php';
        (new ProductVariantController($pdo))->{str_ends_with($route, '/store') ? 'store' : 'index'}((int) $m[1]);
    }
    exit;
}

// 3. Các route Admin đã khai báo theo cấu trúc dự án nhưng chưa triển khai Controller/Model
$currentUser = SessionHelper::getUser();
$unimplementedMeta = null;

if ($route === '/admin' || $route === '/admin/') {
    $unimplementedMeta = ['menu' => 'dashboard', 'title' => 'Tổng quan hệ thống', 'desc' => 'DashboardController chưa được triển khai nghiệp vụ.'];
} elseif (preg_match('~^/admin/categories(?:$|/(?:create|store|delete|(?:edit|update)/[1-9][0-9]*))$~', $route)) {
    $unimplementedMeta = ['menu' => 'categories', 'title' => 'Quản lý danh mục', 'desc' => 'CategoryController chưa được triển khai nghiệp vụ.'];
} elseif (preg_match('~^/admin/orders(?:$|/(?:assign|status|(?:detail/)?[1-9][0-9]*))$~', $route)) {
    $unimplementedMeta = ['menu' => 'orders', 'title' => 'Quản lý đơn hàng', 'desc' => 'OrderController chưa được triển khai nghiệp vụ.'];
} elseif (preg_match('~^/admin/users(?:$|/(?:create|store|toggle-status|(?:detail/)?[1-9][0-9]*))$~', $route)) {
    $unimplementedMeta = ['menu' => 'users', 'title' => 'Người dùng & Phân quyền', 'desc' => 'UserController chưa được triển khai nghiệp vụ.'];
} elseif (preg_match('~^/admin/reviews(?:$|/toggle)$~', $route)) {
    $unimplementedMeta = ['menu' => 'reviews', 'title' => 'Đánh giá & Bình luận', 'desc' => 'ReviewController chưa được triển khai nghiệp vụ.'];
} elseif (preg_match('~^/admin/products/delete$~', $route)
    || preg_match('~^/admin/variants/(?:(?:edit|update)/[1-9][0-9]*|delete)$~', $route)
) {
    $unimplementedMeta = ['menu' => 'products', 'title' => 'Quản lý sản phẩm & biến thể', 'desc' => 'Chức năng mở rộng này chưa được triển khai nghiệp vụ trong Controller.'];
}

if ($unimplementedMeta !== null) {
    http_response_code(501);
    $activeMenu = $unimplementedMeta['menu'];
    $pageTitle = $unimplementedMeta['title'] . ' | ' . APP_NAME;
    require __DIR__ . '/view/layout/header.php';
    ?>
    <div class="card border-0 shadow-sm my-3">
        <div class="card-body p-4 p-md-5 text-center">
            <span class="badge bg-warning text-dark mb-3 px-3 py-2">Chức năng đang phát triển</span>
            <h1 class="h4 fw-bold mb-2"><?= SecurityHelper::e($unimplementedMeta['title']) ?></h1>
            <p class="text-muted mb-2"><?= SecurityHelper::e($unimplementedMeta['desc']) ?></p>
            <p class="text-muted small mb-4">Route hiện tại: <code><?= SecurityHelper::e($route) ?></code> (không tự động chuyển sang dữ liệu demo).</p>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <a href="<?= SecurityHelper::url('/admin/products') ?>" class="btn btn-dtd">
                    <i class="bi bi-phone me-1"></i>Đi tới Quản lý sản phẩm (MVC thật)
                </a>
                <a href="<?= SecurityHelper::e(SecurityHelper::baseUrl() . '/danh-sach-giao-dien.php') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-collection me-1"></i>Xem danh sách giao diện Demo
                </a>
            </div>
        </div>
    </div>
    <?php
    require __DIR__ . '/view/layout/footer.php';
    exit;
}

// 4. Route không tồn tại -> 404
http_response_code(404);
$activeMenu = '';
$pageTitle = '404 - Không tìm thấy trang | ' . APP_NAME;
require __DIR__ . '/view/layout/header.php';
?>
<div class="card border-0 shadow-sm my-3">
    <div class="card-body p-4 p-md-5 text-center">
        <span class="badge bg-danger mb-3 px-3 py-2">HTTP 404</span>
        <h1 class="h4 fw-bold mb-2">Không tìm thấy trang yêu cầu</h1>
        <p class="text-muted mb-4">Đường dẫn <code><?= SecurityHelper::e($route) ?></code> không tồn tại trong hệ thống quản trị.</p>
        <a href="<?= SecurityHelper::url('/admin/products') ?>" class="btn btn-dtd">
            <i class="bi bi-arrow-left me-1"></i>Quay về Quản lý sản phẩm
        </a>
    </div>
</div>
<?php
require __DIR__ . '/view/layout/footer.php';
