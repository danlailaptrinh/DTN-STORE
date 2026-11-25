<?php
use App\Helpers\SecurityHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold mb-0">Thêm điện thoại mới</h1>
    <a href="<?= SecurityHelper::url('/admin/products') ?>" class="btn btn-outline-secondary btn-sm">Quay lại</a>
</div>

<form data-mvc-form="true" data-validate="product-create" action="<?= SecurityHelper::url('/admin/products/store') ?>" method="POST" enctype="multipart/form-data">
    <?= SecurityHelper::csrfField() ?>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h6 fw-bold mb-3">Thông tin điện thoại</h2>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tên điện thoại <span class="text-danger">*</span></label>
                        <input type="text" name="product_name" class="form-control" placeholder="VD: iPhone 17 Pro" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Danh mục (Thương hiệu) <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= (int) $cat['id'] ?>"><?= SecurityHelper::e($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Trạng thái hiển thị</label>
                            <select name="status" class="form-select">
                                <option value="dang_ban" selected>Hiển thị</option>
                                <option value="ngung_ban">Ẩn</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Thương hiệu <span class="text-danger">*</span></label>
                            <input type="text" name="brand" class="form-control" placeholder="VD: Apple, Samsung..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nhãn khuyến mãi</label>
                            <input type="text" name="promotion_label" class="form-control" placeholder="VD: Bán chạy, Hàng mới...">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Mô tả sản phẩm</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Mô tả tổng quan về thiết kế, hiệu năng, camera..."></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Màn hình</label>
                            <input type="text" name="screen" class="form-control" placeholder="VD: 6.7 inch OLED">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Chip xử lý</label>
                            <input type="text" name="chip" class="form-control" placeholder="VD: A19 Pro">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Giá niêm yết (VNĐ)</label>
                            <input type="number" name="list_price" class="form-control" min="0" step="1000" placeholder="VD: 34990000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Giá cũ (VNĐ)</label>
                            <input type="number" name="old_price" class="form-control" min="0" step="1000" placeholder="VD: 37990000">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <!-- Upload hình ảnh -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h2 class="h6 fw-bold mb-2">Hình ảnh điện thoại</h2>
                    <p class="small text-muted mb-3">Hỗ trợ chọn nhiều ảnh (JPG, PNG, WEBP - tối đa 2MB/ảnh). Ảnh đầu tiên sẽ là ảnh đại diện.</p>
                    <input type="file" name="images[]" class="form-control" accept=".jpg,.jpeg,.png,.webp" multiple>
                </div>
            </div>

            <!-- Biến thể đầu tiên -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h6 fw-bold mb-2">Biến thể đầu tiên (nên nhập để bán ngay)</h2>
                    <p class="small text-muted mb-3">Sau khi lưu, bạn có thể thêm nhiều tổ hợp RAM / Dung lượng / Màu sắc khác.</p>

                    <div class="row g-2 mb-2">
                        <div class="col-4">
                            <label class="form-label small fw-semibold">RAM (GB) <span class="text-danger">*</span></label>
                            <input type="number" name="init_ram" class="form-control form-control-sm" min="0" placeholder="8" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Dung lượng (GB) <span class="text-danger">*</span></label>
                            <input type="number" name="init_storage" class="form-control form-control-sm" min="1" placeholder="256" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Màu sắc <span class="text-danger">*</span></label>
                            <input type="text" name="init_color" class="form-control form-control-sm" placeholder="VD: Đen" required>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Mã SKU <span class="text-danger">*</span></label>
                        <input type="text" name="init_sku" class="form-control form-control-sm" placeholder="VD: IP17PM-256-BLK" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="init_sale_price" class="form-control form-control-sm" min="0" step="1000" placeholder="29990000" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Số lượng tồn <span class="text-danger">*</span></label>
                            <input type="number" name="init_stock" class="form-control form-control-sm" min="0" placeholder="10" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-dtd w-100 py-2">
                        <i class="bi bi-save me-1"></i>Lưu điện thoại & Tiếp tục quản lý biến thể
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
