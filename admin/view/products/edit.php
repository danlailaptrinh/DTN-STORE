<?php
use App\Helpers\SecurityHelper;

$productId = (int)($product['product_id'] ?? $product['id'] ?? 0);
$images = is_array($images ?? null) ? $images : [];
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h4 fw-bold mb-1">Sửa sản phẩm: <?= SecurityHelper::e($product['product_name'] ?? $product['name'] ?? '') ?></h1>
        <p class="text-muted small mb-0">Cập nhật thông tin chi tiết của sản phẩm</p>
    </div>
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
        <form data-mvc-form="true" data-validate="product-edit" action="<?= SecurityHelper::url('/admin/products/update/' . $productId) ?>" method="POST" enctype="multipart/form-data">
            <?= SecurityHelper::csrfField() ?>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Tên điện thoại <span class="text-danger">*</span></label>
                <input type="text" name="product_name" maxlength="255" class="form-control" value="<?= SecurityHelper::e($product['product_name'] ?? $product['name'] ?? '') ?>" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Danh mục <span class="text-danger">*</span></label>
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
                    <label class="form-label small fw-semibold">Thương hiệu <span class="text-danger">*</span></label>
                    <input type="text" name="brand" maxlength="120" class="form-control" value="<?= SecurityHelper::e($product['brand'] ?? '') ?>" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Trạng thái hiển thị</label>
                    <select name="status" class="form-select">
                        <option value="dang_ban" <?= ($product['status'] ?? '') === 'dang_ban' || ($product['status'] ?? '') === '1' || ($product['status'] ?? '') === 1 ? 'selected' : '' ?>>Đang bán (Hiển thị)</option>
                        <option value="ngung_ban" <?= ($product['status'] ?? '') === 'ngung_ban' || ($product['status'] ?? '') === '0' || ($product['status'] ?? '') === 0 ? 'selected' : '' ?>>Ngừng bán (Ẩn)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Nhãn khuyến mãi</label>
                    <input type="text" name="promotion_label" maxlength="80" class="form-control" value="<?= SecurityHelper::e($product['promotion_label'] ?? '') ?>" placeholder="VD: Bán chạy, Giảm giá...">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Màn hình</label>
                    <input type="text" name="screen" maxlength="50" class="form-control" value="<?= SecurityHelper::e($product['screen'] ?? '') ?>" placeholder="VD: 6.9 inch OLED">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Chip xử lý</label>
                    <input type="text" name="chip" maxlength="100" class="form-control" value="<?= SecurityHelper::e($product['chip'] ?? '') ?>" placeholder="VD: A18 Pro">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Giá niêm yết (VNĐ)</label>
                    <input type="number" name="list_price" class="form-control" min="0" step="0.01" value="<?= SecurityHelper::e($product['list_price'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Giá cũ (VNĐ)</label>
                    <input type="number" name="old_price" class="form-control" min="0" step="0.01" value="<?= SecurityHelper::e($product['old_price'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Mô tả sản phẩm</label>
                <textarea name="description" class="form-control" rows="4"><?= SecurityHelper::e($product['description'] ?? '') ?></textarea>
            </div>

            <!-- QUẢN LÝ HÌNH ẢNH SẢN PHẨM -->
            <section id="product-images" class="border-top pt-4 mt-4 mb-4">
                <h2 class="h6 fw-bold mb-3"><i class="bi bi-images me-2"></i>Hình ảnh sản phẩm</h2>
                <?php if (!empty($images)): ?>
                    <p class="small text-muted mb-2">Tích chọn để xóa ảnh không còn dùng:</p>
                    <div class="d-flex flex-wrap gap-3 mb-3">
                        <?php foreach ($images as $img): ?>
                            <?php $imgPath = is_array($img) ? ($img['image_path'] ?? '') : (string) $img; ?>
                            <label class="border rounded p-2 text-center bg-white" style="width:120px">
                                <img src="<?= SecurityHelper::productImage($imgPath) ?>" alt="Ảnh sản phẩm" class="rounded" style="width:100px;height:100px;object-fit:contain">
                                <span class="d-block small mt-2 text-danger">
                                    <input type="checkbox" name="delete_images[]" value="<?= SecurityHelper::e($imgPath) ?>"> Xóa ảnh
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted small">Sản phẩm hiện chưa có ảnh.</p>
                <?php endif; ?>

                <label class="form-label small fw-semibold">Tải thêm ảnh mới</label>
                <input type="file" name="images[]" class="form-control" accept=".jpg,.jpeg,.png,.webp" multiple>
                <p class="text-muted small mt-1 mb-0">Hỗ trợ JPG, PNG, WEBP. Ảnh được tích chọn xóa sẽ được gỡ bỏ sau khi lưu.</p>
            </section>

            <button type="submit" class="btn btn-dtd px-4">
                <i class="bi bi-save me-1"></i>Lưu thay đổi
            </button>
        </form>
    </div>
</div>
