<?php
use App\Helpers\SecurityHelper;
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h4 fw-bold mb-1">Quản lý sản phẩm điện thoại</h1>
        <p class="text-muted small mb-0">Tổng số: <strong><?= (int) ($totalItems ?? 0) ?></strong> dòng điện thoại</p>
    </div>
    <a href="<?= SecurityHelper::url('/admin/products/create') ?>" class="btn btn-dtd">
        <i class="bi bi-plus-circle me-1"></i>Thêm điện thoại mới
    </a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
        <?php if ($_GET['success'] === 'created'): ?>
            Đã thêm mới điện thoại thành công!
        <?php elseif ($_GET['success'] === 'updated'): ?>
            Đã cập nhật thông tin sản phẩm thành công!
        <?php else: ?>
            Thao tác thành công!
        <?php endif; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
<?php endif; ?>

<form action="<?= SecurityHelper::url('/admin/products') ?>" method="GET" class="card border-0 shadow-sm mb-4">
    <input type="hidden" name="route" value="/admin/products">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-semibold">Tìm kiếm theo tên điện thoại</label>
                <input type="text" name="keyword" class="form-control" value="<?= SecurityHelper::e($keyword ?? '') ?>" placeholder="VD: iPhone, Galaxy S25...">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Lọc theo danh mục</label>
                <select name="category_id" class="form-select">
                    <option value="0">Tất cả danh mục</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int) $cat['id'] ?>" <?= (int) ($categoryId ?? 0) === (int) $cat['id'] ? 'selected' : '' ?>>
                                <?= SecurityHelper::e($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dtd w-100"><i class="bi bi-search me-1"></i>Tìm kiếm</button>
                <a href="<?= SecurityHelper::url('/admin/products') ?>" class="btn btn-outline-secondary">Đặt lại</a>
            </div>
        </div>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>ID</th>
                    <th>Điện thoại</th>
                    <th>Thương hiệu</th>
                    <th>Khoảng giá</th>
                    <th>Biến thể</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td>#<?= (int) $p['id'] ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img
                                        src="<?= SecurityHelper::productImage($p['primary_image'] ?? '') ?>"
                                        alt="<?= SecurityHelper::e($p['name']) ?>"
                                        style="width: 48px; height: 48px; object-fit: contain;"
                                        class="border rounded bg-white p-1"
                                    >
                                    <div>
                                        <div class="fw-semibold"><?= SecurityHelper::e($p['name']) ?></div>
                                        <?php if ((int) ($p['is_featured'] ?? 0) === 1): ?>
                                            <span class="ui-style-17 badge bg-warning text-dark">Nổi bật</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= SecurityHelper::e($p['category_name'] ?? $p['brand'] ?? '') ?></span></td>
                            <td class="small">
                                <?php if ($p['min_price'] !== null): ?>
                                    <span class="fw-bold text-danger"><?= SecurityHelper::formatPrice($p['min_price']) ?></span>
                                    <?php if ((float) $p['max_price'] > (float) $p['min_price']): ?>
                                        <span class="text-muted"> - <?= SecurityHelper::formatPrice($p['max_price']) ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-warning fw-semibold">Chưa có biến thể</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= SecurityHelper::url('/admin/products/' . (int) $p['id'] . '/variants') ?>" class="btn btn-sm btn-outline-dark">
                                    <i class="bi bi-cpu me-1"></i><?= (int) ($p['variant_count'] ?? 0) ?> biến thể
                                </a>
                            </td>
                            <td>
                                <?= (int) ($p['status'] ?? 0) === 1
                                    ? '<span class="badge bg-success">Hiển thị</span>'
                                    : '<span class="badge bg-secondary">Ẩn</span>' ?>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="<?= SecurityHelper::url('/admin/products/' . (int) $p['id'] . '/images') ?>" class="btn btn-sm btn-outline-info" title="Quản lý ảnh">
                                    <i class="bi bi-images"></i> Ảnh
                                </a>
                                <a href="<?= SecurityHelper::url('/admin/products/edit/' . (int) $p['id']) ?>" class="btn btn-sm btn-outline-primary" title="Sửa">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" disabled title="Chức năng xóa sản phẩm đang phát triển">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Không tìm thấy sản phẩm nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (isset($totalPages) && $totalPages > 1): ?>
    <nav class="mt-4 d-flex justify-content-center">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php
                $url = SecurityHelper::url('/admin/products') . '&' . http_build_query(array_filter([
                    'keyword'     => $keyword ?? '',
                    'category_id' => !empty($categoryId) ? $categoryId : '',
                    'page'        => $i,
                ]));
                ?>
                <li class="page-item <?= $i === ($currentPage ?? 1) ? 'active' : '' ?>">
                    <a class="page-link <?= $i === ($currentPage ?? 1) ? 'bg-danger border-danger' : 'text-danger' ?>" href="<?= SecurityHelper::e($url) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>
