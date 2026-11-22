

<?php

require_once __DIR__ . '/config/pdo.php';
require_once __DIR__ . '/admin/model/Product.php';

$productModel = new Product($pdo);

// Lấy tất cả sản phẩm
$products = $productModel->getAll();

echo "<h2>Danh sách sản phẩm</h2>";

echo "<pre>";
print_r($products);
echo "</pre>";

// Kiểm tra lấy một sản phẩm
$product = $productModel->getById(1);

echo "<h2>Sản phẩm ID = 1</h2>";

echo "<pre>";
print_r($product);
echo "</pre>";
