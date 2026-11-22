<?php

require_once __DIR__ . '/../model/Product.php';

class ProductController
{
    private $productModel;

    public function __construct($pdo)
    {
        $this->productModel = new Product($pdo);
    }

    //Lấy dữ liệu cho trang danh sách sản phẩm

    public function listData()
    {
        // Lấy từ khóa tìm kiếm
        $keyword = $_GET['keyword'] ?? '';

        if (!is_string($keyword)) {
            $keyword = '';
        }

        $keyword = trim($keyword);

        // Lấy id danh mục
        $categoryId = filter_var(
            $_GET['category_id'] ?? 0,
            FILTER_VALIDATE_INT
        );

        $categoryId = max(0, (int) $categoryId);

        // Gọi model lấy dữ liệu
        $products = $this->productModel->getProductList(
            $keyword,
            $categoryId
        );

        $categories = $this->productModel->getCategories();

        // Xử lý hình ảnh sản phẩm
        foreach ($products as &$product) {

            $images = json_decode(
                $product['image_list'] ?? '[]',
                true
            );

            $imageList = [];

            if (is_array($images)) {
                $imageList = $images['anh_san_pham'] ?? $images;
            }

            $image = is_array($imageList)
                ? (string) ($imageList[0] ?? '')
                : '';

            // Nếu chỉ lưu tên file thì thêm đường dẫn
            if ($image !== '' && !str_contains($image, '/')) {
                $image = 'assets/images/products/' . $image;
            }

            $product['primary_image'] = $image;
        }

        unset($product);

        // Phân trang
        $perPage = 10;

        $totalItems = count($products);

        $totalPages = max(
            1,
            (int) ceil($totalItems / $perPage)
        );

        $currentPage = filter_var(
            $_GET['page'] ?? 1,
            FILTER_VALIDATE_INT
        );

        $currentPage = ($currentPage !== false && $currentPage > 0)
            ? $currentPage
            : 1;

        $currentPage = min($currentPage, $totalPages);

        // Tính vị trí bắt đầu
        $offset = ($currentPage - 1) * $perPage;

        // Cắt danh sách sản phẩm theo trang
        $products = array_slice(
            $products,
            $offset,
            $perPage
        );

        return compact(
            'products',
            'categories',
            'keyword',
            'categoryId',
            'totalItems',
            'totalPages',
            'currentPage'
        );
    }

    // Hàm kiểm tra Controller
    public function index()
    {
        //Lấy dữ liệu từ model
        $data = $this->listData();

        // Tạo các biến dùng trong View
        extract($data, EXTR_SKIP);

        // Thông tin giao diện Admin
        $pageTitle = 'Quản lý sản phẩm | DTD STORE';
        $activeMenu = 'products';

        // Lấy thông tin người dùng hiện tại
        $currentUser =
            \App\Helpers\SessionHelper::getUser();

        // Nạp Layout
        require __DIR__ . '/../view/layout/header.php';

        // Nạp View danh sách sản phẩm
        require __DIR__ . '/../view/product/index.php';

        // Nạp Footer
        require __DIR__ . '/../view/layout/footer.php';
    }
}
