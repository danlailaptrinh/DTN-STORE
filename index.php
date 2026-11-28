<?php

use App\Helpers\SecurityHelper;

require_once __DIR__ . '/config/view_helpers.php';

if (!defined('APP_PATH')) {
    define('APP_PATH', __DIR__ . '/view');
}

$route = $_GET['route'] ?? '/';
if (!is_string($route) || strlen($route) > 200) {
    http_response_code(400);
    exit('Route không hợp lệ');
}

$renderClientPage = static function (string $viewFile, array $data = []): void {
    extract($data, EXTR_SKIP);
    $navCategories = $navCategories ?? [];
    require APP_PATH . '/client/layout/header.php';
    require $viewFile;
    require APP_PATH . '/client/layout/footer.php';
};

switch ($route) {
    case '/':
    case '':
        $renderClientPage(APP_PATH . '/client/home.php', [
            'pageTitle' => APP_NAME . ' - Hệ thống bán lẻ điện thoại chính hãng',
            'featuredProducts' => [],
            'promoProducts' => [],
            'newestProducts' => [],
            'bestSellingProducts' => [],
        ]);
        break;

    case '/logout':
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

    case '/products':
    case '/cart':
    case '/checkout':
    case '/orders':
    case '/login':
    case '/register':
    case '/account':
    case '/account/edit':
        http_response_code(501);
        $pageTitle = 'Chức năng đang phát triển | ' . APP_NAME;
        $navCategories = [];
        require APP_PATH . '/client/layout/header.php';
        ?>
        <div class="card border-0 shadow-sm my-4">
            <div class="card-body p-4 p-md-5 text-center">
                <span class="badge bg-warning text-dark mb-3 px-3 py-2">Đang phát triển</span>
                <h1 class="h4 fw-bold mb-2">Chức năng Client đang được hoàn thiện</h1>
                <p class="text-muted mb-4">Đường dẫn <code><?= SecurityHelper::e($route) ?></code> chưa được kết nối Controller/Model.</p>
                <a href="<?= SecurityHelper::url('/') ?>" class="btn btn-danger px-4">Về trang chủ</a>
            </div>
        </div>
        <?php
        require APP_PATH . '/client/layout/footer.php';
        break;

    default:
        if (
            preg_match('~^/(?:product|category)/[a-zA-Z0-9_-]+$~', $route) === 1
            || preg_match('~^/orders/[1-9][0-9]*(?:/(?:qr|success))?$~', $route) === 1
        ) {
            http_response_code(501);
            $pageTitle = 'Chức năng đang phát triển | ' . APP_NAME;
            $navCategories = [];
            require APP_PATH . '/client/layout/header.php';
            ?>
            <div class="card border-0 shadow-sm my-4">
                <div class="card-body p-4 p-md-5 text-center">
                    <span class="badge bg-warning text-dark mb-3 px-3 py-2">Đang phát triển</span>
                    <h1 class="h4 fw-bold mb-2">Chức năng Client đang được hoàn thiện</h1>
                    <p class="text-muted mb-4">Đường dẫn <code><?= SecurityHelper::e($route) ?></code> chưa được kết nối Controller/Model.</p>
                    <a href="<?= SecurityHelper::url('/') ?>" class="btn btn-danger px-4">Về trang chủ</a>
                </div>
            </div>
            <?php
            require APP_PATH . '/client/layout/footer.php';
            break;
        }

        http_response_code(404);
        require APP_PATH . '/errors/404.php';
        break;
}
