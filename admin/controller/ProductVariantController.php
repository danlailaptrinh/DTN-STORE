<?php
require_once __DIR__ . '/../model/Product.php';
require_once __DIR__ . '/../model/ProductVariant.php';
require_once __DIR__ . '/ProductInputValidation.php';
class ProductVariantController
{
    private Product $products;
    private ProductVariant $variants;
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->products = new Product($pdo);
        $this->variants = new ProductVariant($pdo);
    }
    public function store(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Chỉ chấp nhận POST');
        }
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], (string)($_POST['csrf_token'] ?? ''))) {
            http_response_code(403);
            exit('CSRF không hợp lệ');
        }
        if (!$this->products->getById($id)) {
            http_response_code(404);
            exit('Không tìm thấy sản phẩm');
        }
        ProductInputValidation::abort(ProductInputValidation::variant($_POST));
        $data = [
            'sku' => trim($_POST['sku']),
            'ram' => (int)$_POST['ram'],
            'storage' => (int)$_POST['storage'],
            'color' => trim($_POST['color']),
            'sale_price' => $_POST['sale_price'],
            'stock_quantity' => (int)$_POST['stock_quantity'],
            'is_on_sale' => (int)$_POST['is_on_sale']
        ];
        if ($this->variants->existsCombination($id, $data['ram'], $data['storage'], $data['color'])) {
            ProductInputValidation::abort(['Biến thể có cùng RAM, dung lượng và màu sắc đã tồn tại']);
        }
        try {
            $this->variants->create($id, $data);
            header('Location: ' . \App\Helpers\SecurityHelper::url('/admin/products/' . $id . '/variants?success=variant_created'), true, 303);
            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            ProductInputValidation::abort(['Không thể lưu biến thể, có thể SKU đã được sử dụng']);
        }
    }
    public function index(int $id): void
    {
        $product = $this->products->getById($id);
        if (!$product) {
            http_response_code(404);
            exit('Không tìm thấy sản phẩm');
        }
        $variants = $this->variants->getByProductId($id);
        $activeMenu = 'products';
        $pageTitle = 'Quản lý biến thể | DTD STORE';
        require __DIR__ . '/../view/layout/header.php';
        require __DIR__ . '/../view/variants/index.php';
        require __DIR__ . '/../view/layout/footer.php';
    }
    public function edit(int $id): void
    {
        // Lấy thông tin biến thể
        $variant = $this->variants->getById($id);
        // Kiểm tra biến thể tồn tại
        if (!$variant) {
            http_response_code(404);
            exit('Không tìm thấy biến thể');
        }
        // Lấy thông tin sản phẩm của biến thể
        $product = $this->products->getById(
            (int) $variant['product_id']
        );
        // Kiểm tra sản phẩm tồn tại
        if (!$product) {
            http_response_code(404);
            exit('Không tìm thấy sản phẩm');
        }
        // Thiết lập thông tin trang
        $activeMenu = 'products';
        $pageTitle = 'Sửa biến thể | DTD STORE';
        // Hiển thị giao diện
        require __DIR__ . '/../view/layout/header.php';
        require __DIR__ . '/../view/variants/edit.php';
        require __DIR__ . '/../view/layout/footer.php';
    }

    public function update(int $id): void
    {
        // Kiểm tra phương thức gửi dữ liệu
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Chỉ chấp nhận POST');
        }
        // Kiểm tra CSRF
        $token = $_POST['csrf_token'] ?? null;
        if (
            !is_string($token) ||
            !\App\Helpers\SecurityHelper::verifyCsrfToken($token)
        ) {
            http_response_code(403);
            exit('CSRF không hợp lệ');
        }
        // Kiểm tra biến thể tồn tại
        $variant = $this->variants->getById($id);
        if (!$variant) {
            http_response_code(404);
            exit('Không tìm thấy biến thể');
        }
        // Lấy ID sản phẩm
        $productId = (int)$variant['product_id'];
        // Validate dữ liệu
        ProductInputValidation::abort(
            ProductInputValidation::variant($_POST)
        );
        // Lấy dữ liệu từ form
        $data = [
            'sku' => trim($_POST['sku']),
            'ram' => (int)$_POST['ram'],
            'storage' => (int)$_POST['storage'],
            'color' => trim($_POST['color']),
            'sale_price' => $_POST['sale_price'],
            'stock_quantity' => (int)$_POST['stock_quantity'],
            'is_on_sale' => (int)$_POST['is_on_sale']
        ];
        // Kiểm tra trùng biến thể
        if ($this->variants->existsCombinationExcept(
            $productId,
            $data['ram'],
            $data['storage'],
            $data['color'],
            $id
        )) {
            ProductInputValidation::abort([
                'Biến thể có cùng RAM, dung lượng và màu sắc đã tồn tại'
            ]);
        }
        // Cập nhật database
        try {
            $this->variants->update($id, $data);

            header(
                'Location: ' .
                    \App\Helpers\SecurityHelper::url(
                        '/admin/products/' . $productId .
                            '/variants?success=variant_updated'
                    ),
                true,
                303
            );
            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            ProductInputValidation::abort([
                'Không thể cập nhật biến thể, có thể SKU đã được sử dụng'
            ]);
        }
    }


    public function delete(): void
    {
        // Chỉ chấp nhận POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Chỉ chấp nhận POST');
        }
        // Kiểm tra CSRF
        $token = $_POST['csrf_token'] ?? null;
        if (
            !is_string($token) ||
            !\App\Helpers\SecurityHelper::verifyCsrfToken($token)
        ) {
            http_response_code(403);
            exit('CSRF không hợp lệ');
        }
        // Lấy ID biến thể
        $id = filter_var(
            $_POST['variant_id'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );
        if (!$id) {
            http_response_code(400);
            exit('ID biến thể không hợp lệ');
        }
        // Kiểm tra biến thể tồn tại
        $variant = $this->variants->getById($id);
        if (!$variant) {
            http_response_code(404);
            exit('Không tìm thấy biến thể');
        }
        $productId = (int)$variant['product_id'];
        $url = \App\Helpers\SecurityHelper::url(
            '/admin/products/' . $productId . '/variants'
        );
        try {
            $this->pdo->beginTransaction();
            // Khóa sản phẩm để tránh hai yêu cầu
            // xóa biến thể cùng lúc
            $stmt = $this->pdo->prepare(
                "SELECT product_id FROM products
             WHERE product_id = ? FOR UPDATE"
            );
            $stmt->execute([$productId]);
            // Kiểm tra số lượng biến thể
            if ($this->variants->countByProductId($productId) <= 1) {
                $this->pdo->rollBack();
                header(
                    'Location: ' . $url . '&error=last_variant',
                    true,
                    303
                );
                exit;
            }
            // Kiểm tra biến thể đã phát sinh đơn hàng
            if ($this->variants->hasOrders($id)) {
                $this->pdo->rollBack();
                header(
                    'Location: ' . $url . '&error=variant_has_orders',
                    true,
                    303
                );
                exit;
            }
            // Thực hiện xóa
            if (!$this->variants->delete($id)) {
                throw new RuntimeException('Không thể xóa biến thể');
            }
            $this->pdo->commit();
            // Chuyển hướng khi thành công
            header(
                'Location: ' . $url . '&success=variant_deleted',
                true,
                303
            );
            exit;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log($e->getMessage());
            header(
                'Location: ' . $url . '&error=delete_failed',
                true,
                303
            );
            exit;
        }
    }
}
