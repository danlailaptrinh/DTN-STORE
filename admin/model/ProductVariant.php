<?php
class ProductVariant {
    private PDO $pdo;
    public function __construct(PDO $pdo) {$this->pdo=$pdo;}
    public function create(int $productId, array $data): int {
        $sql='INSERT INTO product_variants (product_id,sku,ram,storage,color,sale_price,stock_quantity,is_on_sale) VALUES (?,?,?,?,?,?,?,?)';
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute([$productId,$data['sku'],$data['ram'],$data['storage'],$data['color'],$data['sale_price'],$data['stock_quantity'],$data['is_on_sale']]);
        return (int)$this->pdo->lastInsertId();
    }
    public function existsCombination(int $productId,int $ram,int $storage,string $color): bool {
        $s=$this->pdo->prepare('SELECT COUNT(*) FROM product_variants WHERE product_id=? AND ram=? AND storage=? AND LOWER(TRIM(color))=LOWER(?)');
        $s->execute([$productId,$ram,$storage,trim($color)]);
        return (int)$s->fetchColumn()>0;
    }
    public function getByProductId(int $id): array {
        $s=$this->pdo->prepare('SELECT * FROM product_variants WHERE product_id=? ORDER BY variant_id DESC');
        $s->execute([$id]); return $s->fetchAll(PDO::FETCH_ASSOC);
    }
}
