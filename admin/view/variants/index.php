<?php

use App\Helpers\SecurityHelper;

$productId = (int)($product['product_id'] ?? $product['id'] ?? 0);
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h4 fw-bold mb-1">Quản lý biến thể: <?= SecurityHelper::e($product['product_name'] ?? $product['name'] ?? '') ?></h1>
        <p class="text-muted small mb-0">
            Thương hiệu: <strong><?= SecurityHelper::e($product['brand'] ?? $product['category_name'] ?? '') ?></strong> •
            Mỗi tổ hợp <strong>RAM + Dung lượng + Màu sắc</strong> là duy nhất.
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= SecurityHelper::url('/admin/products/' . $productId . '/images') ?>" class="btn btn-outline-info btn-sm">
            <i class="bi bi-images me-1"></i>Quản lý ảnh
        </a>
        <a href="<?= SecurityHelper::url('/admin/products/edit/' . $productId) ?>" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-pencil me-1"></i>Sửa thông tin máy
        </a>
        <a href="<?= SecurityHelper::url('/admin/products') ?>" class="btn btn-outline-secondary btn-sm">Quay lại</a>
    </div>
</div>

<?php if (($_GET['success'] ?? '') === 'variant_created'): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
        Đã thêm biến thể mới thành công!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
<?php endif; ?>

<?php if (($_GET['success'] ?? '') === 'variant_updated'): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
        Đã cập nhật biến thể thành công!
        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Đóng">
        </button>
    </div>
<?php endif; ?>

<?php if (($_GET['success'] ?? '') === 'variant_deleted'): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4"
        role="alert">
        Đã xóa biến thể thành công!
        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Đóng">
        </button>
    </div>
<?php endif; ?>

<?php
$errorMessages = [
    'last_variant' => 'Không thể xóa biến thể cuối cùng của sản phẩm.',
    'variant_has_orders' => 'Không thể xóa biến thể đã phát sinh đơn hàng.',
    'delete_failed' => 'Xóa biến thể thất bại. Vui lòng thử lại.'
];
$error = $_GET['error'] ?? '';
?>
<?php if (is_string($error) && isset($errorMessages[$error])): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4"
        role="alert">
        <?= SecurityHelper::e($errorMessages[$error]) ?>
        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Đóng">
        </button>
    </div>
<?php endif; ?>
<div class="row g-4">
    <!-- FORM THÊM BIẾN THỂ MỚI -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3"><i class="bi bi-plus-circle text-danger me-1"></i>Thêm biến thể mới</h2>

                <form data-mvc-form="true" data-validate="variant" method="POST" action="<?= SecurityHelper::url('/admin/products/' . $productId . '/variants/store') ?>">
                    <?= SecurityHelper::csrfField() ?>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">RAM (GB) <span class="text-danger">*</span></label>
                        <input type="number" name="ram" class="form-control" min="0" step="1" placeholder="8" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Dung lượng bộ nhớ (GB) <span class="text-danger">*</span></label>
                        <input type="number" name="storage" class="form-control" min="1" step="1" placeholder="256" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Màu sắc <span class="text-danger">*</span></label>
                        <input type="text" name="color" maxlength="80" class="form-control" placeholder="VD: Titan đen, Xanh lam" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Mã SKU <span class="text-danger">*</span></label>
                        <input type="text" name="sku" maxlength="100" pattern="[A-Za-z0-9][A-Za-z0-9._-]*" class="form-control" placeholder="VD: IP-256-BLK" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="sale_price" class="form-control" min="0" max="9999999999999.99" step="0.01" placeholder="VD: 29990000" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Số lượng tồn kho <span class="text-danger">*</span></label>
                        <input type="number" name="stock_quantity" class="form-control" min="0" step="1" placeholder="10" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Khuyến mãi</label>
                        <select name="is_on_sale" class="form-select">
                            <option value="1">Có khuyến mãi</option>
                            <option value="0" selected>Không khuyến mãi</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-dtd w-100">Thêm biến thể</button>
                </form>
            </div>
        </div>
    </div>

    <!-- DANH SÁCH BIẾN THỂ HIỆN CÓ -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="p-4 pb-2">
                    <h2 class="h6 fw-bold mb-0">Danh sách biến thể (<?= count($variants ?? []) ?>)</h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase">
                            <tr>
                                <th>RAM</th>
                                <th>Dung lượng</th>
                                <th>Màu sắc</th>
                                <th>SKU</th>
                                <th class="text-end">Giá bán</th>
                                <th>Tồn kho / KM</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($variants)): ?>
                                <?php foreach ($variants as $v): ?>
                                    <tr>
                                        <td class="fw-semibold"><?= SecurityHelper::e($v['ram']) ?> GB</td>
                                        <td><span class="badge bg-light text-dark border"><?= SecurityHelper::e($v['storage']) ?> GB</span></td>
                                        <td><?= SecurityHelper::e($v['color']) ?></td>
                                        <td><code><?= SecurityHelper::e($v['sku']) ?></code></td>
                                        <td class="text-end fw-bold text-danger">
                                            <?= SecurityHelper::formatPrice($v['sale_price'] ?? $v['price'] ?? 0) ?>
                                        </td>
                                        <td>
                                            <?= (int) ($v['stock_quantity'] ?? $v['stock'] ?? 0) ?> /
                                            <?= ((int) ($v['is_on_sale'] ?? 0) === 1) ? '<span class="badge bg-warning text-dark">KM</span>' : 'Thường' ?>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <a href="<?= SecurityHelper::url('/admin/variants/edit/' . (int)$v['variant_id']) ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Sửa
                                            </a>
                                            <form method="POST"
                                                action="<?= SecurityHelper::url('/admin/variants/delete') ?>"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa biến thể này không?');">

                                                <?= SecurityHelper::csrfField() ?>

                                                <input type="hidden"
                                                    name="variant_id"
                                                    value="<?= (int)$v['variant_id'] ?>">

                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i> Xóa
                                                </button>

                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Chưa có biến thể nào. Hãy thêm biến thể ở khung bên trái.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>