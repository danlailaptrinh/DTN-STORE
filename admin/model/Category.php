
<?php

class Category
{
    private $pdo;

    // Nhận kết nối database
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Lấy danh sách danh mục và tìm kiếm
    public function getAll($keyword = '')
    {
        $sql = "SELECT
                    c.category_id AS id,
                    c.category_name AS name,
                    c.slug,
                    c.description,
                    c.is_visible AS status,
                    COUNT(p.product_id) AS product_count
                FROM categories c
                LEFT JOIN products p
                    ON c.category_id = p.category_id
                WHERE 1 = 1";

        $params = [];

        // Tìm kiếm theo tên, slug hoặc mô tả
        if ($keyword !== '') {
            $sql .= " AND (
                c.category_name LIKE :name
                OR c.slug LIKE :slug
                OR c.description LIKE :description
            )";

            $params['name'] = '%' . $keyword . '%';
            $params['slug'] = '%' . $keyword . '%';
            $params['description'] = '%' . $keyword . '%';
        }

        // Nhóm và sắp xếp danh mục
        $sql .= " GROUP BY
                    c.category_id,
                    c.category_name,
                    c.slug,
                    c.description,
                    c.is_visible
                  ORDER BY c.category_id DESC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kiểm tra slug đã tồn tại hay chưa
    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM categories WHERE slug = :slug";
        $params = ['slug' => $slug];

        if ($excludeId !== null) {
            $sql .= " AND category_id <> :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    // Tạo slug duy nhất từ tên danh mục
    public function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $baseSlug = $this->toSlug($name);
        if ($baseSlug === '') {
            $baseSlug = 'danh-muc';
        }

        $slug = $baseSlug;
        $counter = 2;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // Thêm mới danh mục
    public function create(string $name, string $slug, int $isVisible = 1, ?string $description = null): int
    {
        $sql = "INSERT INTO categories (category_name, slug, is_visible, description)
                VALUES (:name, :slug, :is_visible, :description)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'slug' => $slug,
            'is_visible' => $isVisible === 1 ? 1 : 0,
            'description' => ($description !== null && trim($description) !== '') ? trim($description) : null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    // Lấy thông tin một danh mục theo ID
    public function findById(int $id): ?array
    {
        $sql = "SELECT
                    category_id AS id,
                    category_name AS name,
                    slug,
                    description,
                    is_visible AS status
                FROM categories
                WHERE category_id = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    // Cập nhật thông tin danh mục
    public function update(int $id, string $name, string $slug, int $isVisible = 1, ?string $description = null): bool
    {
        $sql = "UPDATE categories
                SET category_name = :name,
                    slug = :slug,
                    is_visible = :is_visible,
                    description = :description
                WHERE category_id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'is_visible' => $isVisible === 1 ? 1 : 0,
            'description' => ($description !== null && trim($description) !== '') ? trim($description) : null,
        ]);
    }

    // Đếm số sản phẩm đang thuộc danh mục
    public function countProducts(int $id): int
    {
        $sql = "SELECT COUNT(*) FROM products WHERE category_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return (int) $stmt->fetchColumn();
    }

    // Xóa danh mục theo ID
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM categories WHERE category_id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

    // Chuẩn hóa chuỗi tiếng Việt sang slug URL
    private function toSlug(string $text): string
    {
        $text = mb_strtolower(trim($text), 'UTF-8');

        $vietnameseMap = [
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        ];

        foreach ($vietnameseMap as $ascii => $pattern) {
            $text = preg_replace('/(' . $pattern . ')/u', $ascii, $text) ?? $text;
        }

        $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? $text;

        return trim($text, '-');
    }
}
