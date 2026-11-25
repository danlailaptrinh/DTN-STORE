<?php
require_once __DIR__.'/../model/Product.php';
require_once __DIR__.'/../model/ProductVariant.php';
require_once __DIR__.'/ProductInputValidation.php';
class ProductVariantController {
    private Product $products;
    private ProductVariant $variants;
    public function __construct(PDO $pdo){$this->products=new Product($pdo);$this->variants=new ProductVariant($pdo);}
    public function store(int $id): void {
        if ($_SERVER['REQUEST_METHOD']!=='POST') {http_response_code(405);exit('Chỉ chấp nhận POST');}
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'],(string)($_POST['csrf_token']??''))) {http_response_code(403);exit('CSRF không hợp lệ');}
        if (!$this->products->getById($id)) {http_response_code(404);exit('Không tìm thấy sản phẩm');}
        ProductInputValidation::abort(ProductInputValidation::variant($_POST));
        $data=[
            'sku'=>trim($_POST['sku']), 'ram'=>(int)$_POST['ram'], 'storage'=>(int)$_POST['storage'],
            'color'=>trim($_POST['color']), 'sale_price'=>$_POST['sale_price'],
            'stock_quantity'=>(int)$_POST['stock_quantity'], 'is_on_sale'=>(int)$_POST['is_on_sale']
        ];
        if ($this->variants->existsCombination($id,$data['ram'],$data['storage'],$data['color'])) {
            ProductInputValidation::abort(['Biến thể có cùng RAM, dung lượng và màu sắc đã tồn tại']);
        }
        try {
            $this->variants->create($id,$data);
            header('Location: ' . \App\Helpers\SecurityHelper::url('/admin/products/' . $id . '/variants?success=variant_created'), true, 303);
            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            ProductInputValidation::abort(['Không thể lưu biến thể, có thể SKU đã được sử dụng']);
        }
    }
    public function index(int $id): void {
        $product=$this->products->getById($id);
        if (!$product){http_response_code(404);exit('Không tìm thấy sản phẩm');}
        $variants=$this->variants->getByProductId($id);
        $activeMenu='products';$pageTitle='Quản lý biến thể | DTD STORE';
        require __DIR__.'/../view/layout/header.php';
        require __DIR__.'/../view/variants/index.php';
        require __DIR__.'/../view/layout/footer.php';
    }
}
