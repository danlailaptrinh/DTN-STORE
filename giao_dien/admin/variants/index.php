<?php
use App\Helpers\SecurityHelper;
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h4 fw-bold mb-1">Quản lý biến thể: <?= SecurityHelper::e($product['name']) ?></h1>
        <p class="text-muted small mb-0">
            Thương hiệu: <strong><?= SecurityHelper::e($product['category_name']) ?></strong> •
            Mỗi tổ hợp <strong>RAM + Dung lượng + Màu sắc</strong> là duy nhất.
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= SecurityHelper::url('/admin/products/' . (int) $product['id'] . '/images') ?>" class="btn btn-outline-info btn-sm">
            <i class="bi bi-images me-1"></i>Quản lý ảnh
        </a>
        <a href="<?= SecurityHelper::url('/admin/products/edit/' . (int) $product['id']) ?>" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-pencil me-1"></i>Sửa thông tin máy
        </a>
        <a href="<?= SecurityHelper::url('/admin/products') ?>" class="btn btn-outline-secondary btn-sm">Quay lại</a>
    </div>
</div>

<div class="row g-4">
    <!-- FORM THÊM BIẾN THỂ MỚI -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3"><i class="bi bi-plus-circle text-danger me-1"></i>Thêm biến thể mới</h2>

                <form action="<?= SecurityHelper::url('/admin/products/' . (int) $product['id'] . '/variants/store') ?>" method="POST">
                    <?= SecurityHelper::csrfField() ?>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">RAM <span class="text-danger">*</span></label>
                        <input type="text" name="ram" class="form-control" placeholder="VD: 8GB, 12GB, 16GB" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Dung lượng bộ nhớ <span class="text-danger">*</span></label>
                        <input type="text" name="storage" class="form-control" placeholder="VD: 128GB, 256GB, 512GB, 1TB" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Màu sắc <span class="text-danger">*</span></label>
                        <input type="text" name="color" class="form-control" placeholder="VD: Đen, Bạc, Cam Vũ Trụ" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Giá bán gốc (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" min="1000" step="1000" placeholder="VD: 37990000" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Giá khuyến mãi (VNĐ)</label>
                        <input type="number" name="sale_price" class="form-control" min="1000" step="1000" placeholder="Để trống nếu không giảm">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="1" selected>Đang kinh doanh</option>
                            <option value="0">Ngừng hiển thị</option>
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
                    <h2 class="h6 fw-bold mb-0">Danh sách biến thể (<?= count($variants) ?>)</h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase">
                            <tr>
                                <th>RAM</th>
                                <th>Dung lượng</th>
                                <th>Màu sắc</th>
                                <th class="text-end">Giá gốc</th>
                                <th class="text-end">Giá KM</th>
                                <th>Trạng thái</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($variants)): ?>
                                <?php foreach ($variants as $v): ?>
                                    <tr>
                                        <td class="fw-semibold"><?= SecurityHelper::e($v['ram']) ?></td>
                                        <td><span class="badge bg-light text-dark border"><?= SecurityHelper::e($v['storage']) ?></span></td>
                                        <td><?= SecurityHelper::e($v['color']) ?></td>
                                        <td class="text-end"><?= SecurityHelper::formatPrice($v['price']) ?></td>
                                        <td class="text-end fw-bold text-danger">
                                            <?= $v['sale_price'] !== null ? SecurityHelper::formatPrice($v['sale_price']) : '-' ?>
                                        </td>
                                        <td>
                                            <?= (int) $v['status'] === 1
                                                ? '<span class="badge bg-success">Hiển thị</span>'
                                                : '<span class="badge bg-secondary">Ẩn</span>' ?>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <a href="<?= SecurityHelper::url('/admin/variants/edit/' . (int) $v['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Sửa
                                            </a>
                                            <form action="<?= SecurityHelper::url('/admin/variants/delete') ?>" method="POST" class="d-inline">
                                                <?= SecurityHelper::csrfField() ?>
                                                <input type="hidden" name="variant_id" value="<?= (int) $v['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xác nhận xóa biến thể này?');">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center text-muted py-4">Chưa có biến thể nào. Hãy thêm biến thể ở khung bên trái.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
