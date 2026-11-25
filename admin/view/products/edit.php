<?php
use App\Helpers\SecurityHelper;

$productId = (int)($product['product_id'] ?? $product['id'] ?? 0);
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <h1 class="h4 fw-bold mb-0">Sửa sản phẩm: <?= SecurityHelper::e($product['product_name'] ?? $product['name'] ?? '') ?></h1>
    <div class="d-flex gap-2">
        <a href="<?= SecurityHelper::url('/admin/products/' . $productId . '/images') ?>" class="btn btn-outline-info btn-sm">
            <i class="bi bi-images me-1"></i>Quản lý ảnh
        </a>
        <a href="<?= SecurityHelper::url('/admin/products/' . $productId . '/variants') ?>" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-cpu me-1"></i>Quản lý biến thể
        </a>
        <a href="<?= SecurityHelper::url('/admin/products') ?>" class="btn btn-outline-secondary btn-sm">Quay lại</a>
    </div>
</div>

<div class="ui-style-15 card border-0 shadow-sm">
    <div class="card-body p-4">
        <form data-mvc-form="true" data-validate="product-edit" action="<?= SecurityHelper::url('/admin/products/update/' . $productId) ?>" method="POST">
            <?= SecurityHelper::csrfField() ?>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Tên điện thoại <span class="text-danger">*</span></label>
                <input type="text" name="product_name" maxlength="255" class="form-control" value="<?= SecurityHelper::e($product['product_name'] ?? $product['name'] ?? '') ?>" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Danh mục (Thương hiệu) <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= (int) $cat['id'] ?>" <?= (int) ($product['category_id'] ?? 0) === (int) $cat['id'] ? 'selected' : '' ?>>
                                    <?= SecurityHelper::e($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Trạng thái hiển thị</label>
                    <select name="status" class="form-select">
                        <option value="dang_ban" <?= ($product['status'] ?? '') === 'dang_ban' || ($product['status'] ?? '') === '1' || ($product['status'] ?? '') === 1 ? 'selected' : '' ?>>Hiển thị</option>
                        <option value="ngung_ban" <?= ($product['status'] ?? '') === 'ngung_ban' || ($product['status'] ?? '') === '0' || ($product['status'] ?? '') === 0 ? 'selected' : '' ?>>Ẩn</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold">Mô tả sản phẩm</label>
                <textarea name="description" class="form-control" rows="4"><?= SecurityHelper::e($product['description'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-dtd px-4">Lưu thay đổi</button>
        </form>
    </div>
</div>
