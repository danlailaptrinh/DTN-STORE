<?php
class Product
{
    private $pdo;

    // Nhận kết nối database từ bên ngoài
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Lấy tất cả sản phẩm
    public function getAll()
    {
        $sql = "SELECT
            p.*,
            c.category_name
        FROM products p
        LEFT JOIN categories c
            ON p.category_id = c.category_id
        ORDER BY p.product_id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy một sản phẩm theo ID
    public function getById($id)
    {
        $sql = "SELECT *
                FROM products
                WHERE product_id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách sản phẩm

    public function getProductList($keyword = "", $categoryId = 0)
    {
        $sql = "SELECT
        p.product_id AS id,
        p.product_name AS name,
        p.brand,
        p.category_id,
        c.category_name,
        p.image_list,
p.is_featured,
CASE
    WHEN p.status = 'dang_ban'
    THEN 1 ELSE 0
END AS status,
        COUNT(v.variant_id) AS variant_count,
        MIN(v.sale_price) AS min_price,
        MAX(v.sale_price) AS max_price
    FROM products p
    LEFT JOIN categories c
        ON p.category_id = c.category_id
    LEFT JOIN product_variants v
        ON p.product_id = v.product_id
    WHERE 1 = 1";

        $params = [];

        if ($keyword !== '') {
            $sql .= " AND p.product_name LIKE :keyword";
            $params['keyword'] = '%' . $keyword . '%';
        }

        if ($categoryId > 0) {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        $sql .= " GROUP BY p.product_id, c.category_name
                  ORDER BY p.product_id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Lấy danh sách danh mục
    public function getCategories()
    {
        $sql = "SELECT
        category_id AS id,
        category_name AS name
        FROM categories ORDER BY category_name ASC";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
