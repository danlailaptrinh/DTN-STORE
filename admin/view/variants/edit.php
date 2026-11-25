<?php

use App\Helpers\SecurityHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold mb-0">
        Sửa biến thể:
        <?= SecurityHelper::e($product['product_name'] ?? $product['name'] ?? '') ?>
    </h1>

    <a href="<?= SecurityHelper::url('/admin/products/' . (int)$variant['product_id'] . '/variants') ?>"
        class="btn btn-outline-secondary btn-sm">
        Quay lại
    </a>
</div>

<div class="ui-style-11 card border-0 shadow-sm">
    <div class="card-body p-4">

        <form action="<?= SecurityHelper::url('/admin/variants/update/' . (int)$variant['variant_id']) ?>"
            method="POST">

            <?= SecurityHelper::csrfField() ?>

            <!-- RAM, dung lượng, màu sắc -->
            <div class="row g-3 mb-3">

                <div class="col-md-4">
                    <label class="form-label small fw-semibold">
                        RAM (GB) <span class="text-danger">*</span>
                    </label>

                    <input type="number" name="ram"
                        class="form-control"
                        min="0" step="1"
                        value="<?= (int)$variant['ram'] ?>"
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold">
                        Dung lượng (GB) <span class="text-danger">*</span>
                    </label>

                    <input type="number" name="storage"
                        class="form-control"
                        min="1" step="1"
                        value="<?= (int)$variant['storage'] ?>"
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold">
                        Màu sắc <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="color"
                        class="form-control"
                        maxlength="80"
                        value="<?= SecurityHelper::e($variant['color']) ?>"
                        required>
                </div>

            </div>

            <!-- SKU -->
            <div class="mb-3">
                <label class="form-label small fw-semibold">
                    Mã SKU <span class="text-danger">*</span>
                </label>

                <input type="text" name="sku"
                    class="form-control"
                    maxlength="100"
                    pattern="[A-Za-z0-9][A-Za-z0-9._-]*"
                    value="<?= SecurityHelper::e($variant['sku']) ?>"
                    required>
            </div>

            <!-- Giá bán và tồn kho -->
            <div class="row g-3 mb-3">

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        Giá bán (VNĐ) <span class="text-danger">*</span>
                    </label>

                    <input type="number" name="sale_price"
                        class="form-control"
                        min="0" step="0.01"
                        max="9999999999999.99"
                        value="<?= SecurityHelper::e($variant['sale_price']) ?>"
                        required>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        Số lượng tồn kho <span class="text-danger">*</span>
                    </label>

                    <input type="number" name="stock_quantity"
                        class="form-control"
                        min="0" step="1"
                        value="<?= (int)$variant['stock_quantity'] ?>"
                        required>
                </div>

            </div>

            <!-- Khuyến mãi -->
            <div class="mb-4">
                <label class="form-label small fw-semibold">
                    Khuyến mãi
                </label>

                <select name="is_on_sale" class="form-select">

                    <option value="1"
                        <?= (int)$variant['is_on_sale'] === 1 ? 'selected' : '' ?>>
                        Có khuyến mãi
                    </option>

                    <option value="0"
                        <?= (int)$variant['is_on_sale'] === 0 ? 'selected' : '' ?>>
                        Không khuyến mãi
                    </option>

                </select>
            </div>

            <button type="submit" class="btn btn-dtd px-4">
                Lưu thay đổi
            </button>

        </form>
    </div>
</div>