<?php
class CategoryController
{
    private $categoryModel;
    // Nhận kết nối database từ Router
    public function __construct($pdo)
    {
        require_once __DIR__ . '/../model/Category.php';
        $this->categoryModel = new Category($pdo);
    }
    // Hiển thị danh sách danh mục
    public function index(): void
    {
        $keyword = trim((string) ($_GET['keyword'] ?? ''));

        // Lấy danh sách danh mục từ Model
        $categories = $this->categoryModel->getAll($keyword);

        // Phân trang danh sách danh mục
        $perPage = 10;
        $totalItems = count($categories);
        $totalPages = max(1, (int) ceil($totalItems / $perPage));
        $currentPage = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT);
        $currentPage = ($currentPage !== false && $currentPage > 0) ? $currentPage : 1;
        $currentPage = min($currentPage, $totalPages);
        $offset = ($currentPage - 1) * $perPage;
        $categories = array_slice($categories, $offset, $perPage);

        // Thông tin giao diện Admin
        $pageTitle = 'Quản lý danh mục | ' . (defined('APP_NAME') ? APP_NAME : 'DTD STORE');
        $activeMenu = 'categories';
        $currentUser = \App\Helpers\SessionHelper::getUser();

        // Nạp Layout và View danh sách danh mục
        require __DIR__ . '/../view/layout/header.php';
        require __DIR__ . '/../view/categories/index.php';
        require __DIR__ . '/../view/layout/footer.php';
    }

    // Hiển thị form thêm danh mục mới
    public function create(): void
    {
        $pageTitle = 'Thêm danh mục | ' . (defined('APP_NAME') ? APP_NAME : 'DTD STORE');
        $activeMenu = 'categories';
        $currentUser = \App\Helpers\SessionHelper::getUser();

        require __DIR__ . '/../view/layout/header.php';
        require __DIR__ . '/../view/categories/create.php';
        require __DIR__ . '/../view/layout/footer.php';
    }

    // Xử lý lưu danh mục mới
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Chỉ chấp nhận POST');
        }

        if (!\App\Helpers\SecurityHelper::verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            http_response_code(403);
            exit('CSRF Token không hợp lệ');
        }

        $name = \App\Helpers\SecurityHelper::cleanInput($_POST['name'] ?? '');
        $description = \App\Helpers\SecurityHelper::cleanInput($_POST['description'] ?? '');
        $status = ((string) ($_POST['status'] ?? '1') === '0') ? 0 : 1;

        if ($name === '') {
            \App\Helpers\SessionHelper::setFlash('danger', 'Vui lòng nhập tên danh mục.');
            header('Location: ' . \App\Helpers\SecurityHelper::url('/admin/categories/create'), true, 302);
            exit;
        }

        if (mb_strlen($name, 'UTF-8') > 150) {
            \App\Helpers\SessionHelper::setFlash('danger', 'Tên danh mục không được vượt quá 150 ký tự.');
            header('Location: ' . \App\Helpers\SecurityHelper::url('/admin/categories/create'), true, 302);
            exit;
        }

        $slug = $this->categoryModel->generateUniqueSlug($name);
        $this->categoryModel->create($name, $slug, $status, $description !== '' ? $description : null);

        \App\Helpers\SessionHelper::setFlash('success', 'Thêm danh mục thành công!');
        header('Location: ' . \App\Helpers\SecurityHelper::url('/admin/categories'), true, 302);
        exit;
    }

    // Hiển thị form sửa danh mục
    public function edit(int $id): void
    {
        $category = $this->categoryModel->findById($id);
        if (!$category) {
            \App\Helpers\SessionHelper::setFlash('danger', 'Không tìm thấy danh mục.');
            header('Location: ' . \App\Helpers\SecurityHelper::url('/admin/categories'), true, 302);
            exit;
        }

        $pageTitle = 'Sửa danh mục | ' . (defined('APP_NAME') ? APP_NAME : 'DTD STORE');
        $activeMenu = 'categories';
        $currentUser = \App\Helpers\SessionHelper::getUser();

        require __DIR__ . '/../view/layout/header.php';
        require __DIR__ . '/../view/categories/edit.php';
        require __DIR__ . '/../view/layout/footer.php';
    }

    // Xử lý cập nhật danh mục
    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Chỉ chấp nhận POST');
        }

        if (!\App\Helpers\SecurityHelper::verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            http_response_code(403);
            exit('CSRF Token không hợp lệ');
        }

        $category = $this->categoryModel->findById($id);
        if (!$category) {
            \App\Helpers\SessionHelper::setFlash('danger', 'Không tìm thấy danh mục.');
            header('Location: ' . \App\Helpers\SecurityHelper::url('/admin/categories'), true, 302);
            exit;
        }

        $name = \App\Helpers\SecurityHelper::cleanInput($_POST['name'] ?? '');
        $description = \App\Helpers\SecurityHelper::cleanInput($_POST['description'] ?? '');
        $status = ((string) ($_POST['status'] ?? '1') === '0') ? 0 : 1;

        if ($name === '') {
            \App\Helpers\SessionHelper::setFlash('danger', 'Vui lòng nhập tên danh mục.');
            header('Location: ' . \App\Helpers\SecurityHelper::url('/admin/categories/edit/' . $id), true, 302);
            exit;
        }

        if (mb_strlen($name, 'UTF-8') > 150) {
            \App\Helpers\SessionHelper::setFlash('danger', 'Tên danh mục không được vượt quá 150 ký tự.');
            header('Location: ' . \App\Helpers\SecurityHelper::url('/admin/categories/edit/' . $id), true, 302);
            exit;
        }

        $slug = $this->categoryModel->generateUniqueSlug($name, $id);
        $this->categoryModel->update($id, $name, $slug, $status, $description !== '' ? $description : null);

        \App\Helpers\SessionHelper::setFlash('success', 'Cập nhật danh mục thành công!');
        header('Location: ' . \App\Helpers\SecurityHelper::url('/admin/categories'), true, 302);
        exit;
    }

    // Xử lý xóa danh mục
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Chỉ chấp nhận POST');
        }

        if (!\App\Helpers\SecurityHelper::verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            http_response_code(403);
            exit('CSRF Token không hợp lệ');
        }

        $categoryId = filter_var($_POST['category_id'] ?? 0, FILTER_VALIDATE_INT);
        if ($categoryId === false || $categoryId <= 0 || !$this->categoryModel->findById((int) $categoryId)) {
            \App\Helpers\SessionHelper::setFlash('danger', 'Không tìm thấy danh mục.');
            header('Location: ' . \App\Helpers\SecurityHelper::url('/admin/categories'), true, 302);
            exit;
        }

        if ($this->categoryModel->countProducts((int) $categoryId) > 0) {
            \App\Helpers\SessionHelper::setFlash('danger', 'Không thể xóa danh mục đang có sản phẩm. Vui lòng ẩn danh mục thay vì xóa.');
            header('Location: ' . \App\Helpers\SecurityHelper::url('/admin/categories'), true, 302);
            exit;
        }

        $this->categoryModel->delete((int) $categoryId);

        \App\Helpers\SessionHelper::setFlash('success', 'Đã xóa danh mục thành công!');
        header('Location: ' . \App\Helpers\SecurityHelper::url('/admin/categories'), true, 302);
        exit;
    }
}
