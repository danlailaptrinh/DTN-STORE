<?php

require_once __DIR__ . '/config/pdo.php';
require_once __DIR__ . '/admin/model/Product.php';

$model = new Product($pdo);

echo '<pre>';

print_r($model->getProductList());

print_r($model->getCategories());

echo '</pre>';
