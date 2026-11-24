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

if (!is_string($route)) {
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
    header('Location: /index.php', true, 302);
    exit;
}

// 2. Xử lý các route LIVE MVC Sản phẩm & Biến thể kết nối MySQL
if (preg_match('~^/admin/products(?:$|/(?:create|store|edit/[1-9][0-9]*|update/[1-9][0-9]*|[1-9][0-9]*/variants(?:/store)?)$)~', $route)) {
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
    } elseif (preg_match('~^/admin/products/([1-9][0-9]*)/variants(?:/store)?$~', $route, $m)) {
        require_once __DIR__ . '/controller/ProductVariantController.php';
        (new ProductVariantController($pdo))->{str_ends_with($route, '/store') ? 'store' : 'index'}((int) $m[1]);
    }
    exit;
}

// 3. Dispatcher hiển thị giao diện đã cắt cho các trang Admin khác
// Chuẩn bị dữ liệu mẫu hiển thị
$demoData = require __DIR__ . '/../demo/data.php';
extract($demoData);

function findRowById(array $rows, $id): array {
    foreach ($rows as $r) {
        if (($r['id'] ?? null) == $id || ($r['product_id'] ?? null) == $id) {
            return $r;
        }
    }
    return $rows[0] ?? [];
}

function filterBy(array $rows, string $key, $val): array {
    return array_values(array_filter($rows, fn($r) => ($r[$key] ?? null) == $val));
}

// Bổ sung thông tin người dùng, danh mục, sản phẩm, đơn hàng
foreach ($users as &$u) {
    $role = findRowById($roles, $u['role_id']);
    $u['role_name'] = $role['name'] ?? 'user';
}
unset($u);

foreach ($categories as &$c) {
    $c['product_count'] = count(filterBy($products, 'category_id', $c['id']));
}
unset($c);

foreach ($reviews as &$r) {
    $u = findRowById($users, $r['user_id']);
    $p = findRowById($products, $r['product_id']);
    $r += [
        'full_name'      => $u['full_name'] ?? '',
        'user_name'      => $u['full_name'] ?? '',
        'user_email'     => $u['email'] ?? '',
        'product_name'   => $p['name'] ?? '',
        'product_slug'   => $p['slug'] ?? '',
        'product_status' => $p['status'] ?? 1,
    ];
}
unset($r);

foreach ($products as &$p) {
    $c = findRowById($categories, $p['category_id']);
    $vs = filterBy($product_variants, 'product_id', $p['id']);
    usort($vs, fn($a, $b) => ($a['sale_price'] ?? $a['price']) <=> ($b['sale_price'] ?? $b['price']));
    $v = $vs[0] ?? ['price' => 0, 'sale_price' => null];
    $rs = filterBy($reviews, 'product_id', $p['id']);
    $prices = array_map(fn($item) => $item['sale_price'] ?? $item['price'], $vs);
    $pImgs = filterBy($product_images, 'product_id', $p['id']);
    $p += [
        'category_name'         => $c['name'] ?? '',
        'category_slug'         => $c['slug'] ?? '',
        'primary_image'         => $pImgs[0]['image_path'] ?? '',
        'display_original_price'=> $v['price'],
        'display_sale_price'    => $v['sale_price'],
        'min_price'             => !empty($prices) ? min($prices) : null,
        'max_price'             => !empty($prices) ? max($prices) : null,
        'variant_count'         => count($vs),
        'avg_rating'            => $rs ? array_sum(array_column($rs, 'rating')) / count($rs) : null,
        'review_count'          => count($rs),
    ];
}
unset($p);

foreach ($orders as &$o) {
    $pay = filterBy($payments, 'order_id', $o['id'])[0] ?? [];
    $u = findRowById($users, $o['user_id']);
    $assigns = filterBy($order_assignments, 'order_id', $o['id']);
    $activeAssign = array_values(array_filter($assigns, fn($x) => !empty($x['is_active'])))[0] ?? [];
    $emp = $activeAssign ? findRowById($users, $activeAssign['employee_id']) : [];
    $o += [
        'customer_name'        => $u['full_name'] ?? '',
        'customer_email'       => $u['email'] ?? '',
        'active_employee_id'   => $emp['id'] ?? null,
        'active_employee_name' => $emp['full_name'] ?? null,
        'employee_name'        => $emp['full_name'] ?? null,
        'payment_status'       => $pay['payment_status'] ?? 'unpaid',
        'payment_amount'       => $pay['amount'] ?? 0,
    ];
}
unset($o);

$stats = [
    'total_revenue'    => 36990000,
    'total_products'   => count($products),
    'total_categories' => count($categories),
    'total_customers'  => 3,
    'total_employees'  => 2,
    'total_orders'     => count($orders),
    'total_assigned'   => 2,
    'pending_count'    => 1,
    'confirmed_count'  => 1,
    'shipping_count'   => 1,
    'delivered_count'  => 1,
];
$revenueChart = [
    ['order_date' => date('Y-m-d', strtotime('-4 days')), 'daily_revenue' => 12500000],
    ['order_date' => date('Y-m-d', strtotime('-3 days')), 'daily_revenue' => 18900000],
    ['order_date' => date('Y-m-d', strtotime('-2 days')), 'daily_revenue' => 25400000],
    ['order_date' => date('Y-m-d', strtotime('-1 days')), 'daily_revenue' => 31200000],
    ['order_date' => date('Y-m-d'), 'daily_revenue' => 36990000],
];
$latestOrders = $orders;
$currentUser = SessionHelper::getUser();
$id = (int) preg_replace('/\D/', '', $route);
$keyword = $_GET['keyword'] ?? '';
$filters = ['keyword' => $keyword, 'status' => $_GET['status'] ?? '', 'payment_status' => $_GET['payment_status'] ?? '', 'assignment' => $_GET['assignment'] ?? '', 'rating' => $_GET['rating'] ?? ''];
$roleFilter = $_GET['role'] ?? '';
$currentPage = 1;
$totalPages = 1;

// Định tuyến và nạp view đã cắt
$viewFile = null;
$pageTitle = 'Quản trị | ' . APP_NAME;
$activeMenu = 'dashboard';

if ($route === '/admin' || $route === '/admin/') {
    $activeMenu = 'dashboard';
    $pageTitle = 'Tổng quan hệ thống | ' . APP_NAME;
    $viewFile = __DIR__ . '/view/dashboard.php';
} elseif ($route === '/admin/categories') {
    $activeMenu = 'categories';
    $pageTitle = 'Quản lý danh mục | ' . APP_NAME;
    $totalItems = count($categories);
    $viewFile = __DIR__ . '/view/categories/index.php';
} elseif ($route === '/admin/categories/create') {
    $activeMenu = 'categories';
    $pageTitle = 'Thêm danh mục mới | ' . APP_NAME;
    $viewFile = __DIR__ . '/view/categories/create.php';
} elseif (str_starts_with($route, '/admin/categories/edit/')) {
    $activeMenu = 'categories';
    $category = findRowById($categories, $id ?: 1);
    $pageTitle = 'Sửa danh mục | ' . APP_NAME;
    $viewFile = __DIR__ . '/view/categories/edit.php';
} elseif ($route === '/admin/orders') {
    $activeMenu = 'orders';
    $pageTitle = 'Quản lý đơn hàng | ' . APP_NAME;
    $totalItems = count($orders);
    $viewFile = __DIR__ . '/view/orders/index.php';
} elseif (str_starts_with($route, '/admin/orders/') || str_starts_with($route, '/admin/orders/detail/')) {
    $activeMenu = 'orders';
    $order = findRowById($orders, $id ?: 1);
    $details = filterBy($order_details, 'order_id', $order['id'] ?? 1);
    foreach ($details as &$it) {
        $v = findRowById($product_variants, $it['variant_id'] ?? 1);
        $it['product_slug'] = findRowById($products, $v['product_id'] ?? 1)['slug'] ?? '';
        $it['primary_image'] = $it['product_image'] ?? '';
    }
    unset($it);
    $employees = filterBy($users, 'role_name', 'employee');
    $assignmentHistory = filterBy($order_assignments, 'order_id', $order['id'] ?? 1);
    foreach ($assignmentHistory as &$a) {
        $e = findRowById($users, $a['employee_id']);
        $a += ['employee_name' => $e['full_name'] ?? '', 'employee_email' => $e['email'] ?? '', 'assigned_by_name' => 'Nguyễn Quản Trị'];
    }
    unset($a);
    $pageTitle = 'Chi tiết đơn hàng | ' . APP_NAME;
    $viewFile = __DIR__ . '/view/orders/detail.php';
} elseif ($route === '/admin/users') {
    $activeMenu = 'users';
    $pageTitle = 'Quản lý người dùng & Phân quyền | ' . APP_NAME;
    $totalItems = count($users);
    $viewFile = __DIR__ . '/view/users/index.php';
} elseif ($route === '/admin/users/create') {
    $activeMenu = 'users';
    $pageTitle = 'Tạo tài khoản người dùng | ' . APP_NAME;
    $viewFile = __DIR__ . '/view/users/create.php';
} elseif (str_starts_with($route, '/admin/users/') || str_starts_with($route, '/admin/users/detail/')) {
    $activeMenu = 'users';
    $targetUser = findRowById($users, $id ?: 4);
    $pageTitle = 'Chi tiết tài khoản | ' . APP_NAME;
    $viewFile = __DIR__ . '/view/users/detail.php';
} elseif ($route === '/admin/reviews') {
    $activeMenu = 'reviews';
    $pageTitle = 'Đánh giá & Bình luận | ' . APP_NAME;
    $totalItems = count($reviews);
    $viewFile = __DIR__ . '/view/reviews/index.php';
} elseif (preg_match('~^/admin/products/([1-9][0-9]*)/images~', $route, $m)) {
    $activeMenu = 'products';
    $prodId = (int) $m[1];
    $product = findRowById($products, $prodId);
    $images = filterBy($product_images, 'product_id', $prodId);
    $pageTitle = 'Quản lý hình ảnh | ' . APP_NAME;
    $viewFile = __DIR__ . '/view/products/images.php';
} elseif (preg_match('~^/admin/variants/edit/([1-9][0-9]*)~', $route, $m)) {
    $activeMenu = 'products';
    $variant = findRowById($product_variants, (int) $m[1]);
    $product = findRowById($products, $variant['product_id'] ?? 1);
    $pageTitle = 'Sửa biến thể | ' . APP_NAME;
    $viewFile = __DIR__ . '/view/variants/edit.php';
}

if ($viewFile && is_file($viewFile)) {
    require __DIR__ . '/view/layout/header.php';
    require $viewFile;
    require __DIR__ . '/view/layout/footer.php';
    exit;
}

// Route không tìm thấy
http_response_code(404);
$pageTitle = 'Không tìm thấy trang | ' . APP_NAME;
require __DIR__ . '/view/layout/header.php';
echo '<div class="alert alert-warning my-5 text-center"><h4>Không tìm thấy trang yêu cầu</h4><p>Đường dẫn <code>' . SecurityHelper::e($route) . '</code> không tồn tại.</p><a href="' . SecurityHelper::url('/admin') . '" class="btn btn-dtd">Về tổng quan</a></div>';
require __DIR__ . '/view/layout/footer.php';
