<?php
use App\Helpers\SecurityHelper;

$productId = (int) ($product['product_id'] ?? $product['id'] ?? 0);
$images = is_array($images ?? null) ? $images : [];
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h4 fw-bold mb-1">Quản lý hình ảnh: <?= SecurityHelper::e($product['product_name'] ?? $product['name'] ?? '') ?></h1>
        <p class="text-muted small mb-0">Thương hiệu: <strong><?= SecurityHelper::e($product['category_name'] ?? $product['brand'] ?? '') ?></strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= SecurityHelper::url('/admin/products/' . $productId . '/variants') ?>" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-cpu me-1"></i>Quản lý biến thể
        </a>
        <a href="<?= SecurityHelper::url('/admin/products') ?>" class="btn btn-outline-secondary btn-sm">Quay lại danh sách</a>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
        <?php if ($_GET['success'] === 'uploaded'): ?>
            Đã tải ảnh lên thành công!
        <?php elseif ($_GET['success'] === 'primary'): ?>
            Đã đặt ảnh đại diện chính thành công!
        <?php elseif ($_GET['success'] === 'deleted'): ?>
            Đã xóa hình ảnh thành công!
        <?php endif; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
<?php endif; ?>

<!-- FORM UPLOAD THÊM ẢNH -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h2 class="h6 fw-bold mb-2">Tải lên hình ảnh mới</h2>
        <p class="small text-muted mb-3">Chấp nhận JPG, JPEG, PNG, WEBP (tối đa 5MB mỗi ảnh). Có thể chọn cùng lúc nhiều ảnh.</p>

        <form data-mvc-form="true" action="<?= SecurityHelper::url('/admin/products/' . $productId . '/images/upload') ?>" method="POST" enctype="multipart/form-data" class="row g-3 align-items-center">
            <?= SecurityHelper::csrfField() ?>
            <div class="col-md-8">
                <input type="file" name="images[]" class="form-control" accept=".jpg,.jpeg,.png,.webp" multiple required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-dtd w-100">
                    <i class="bi bi-cloud-upload me-1"></i>Tải ảnh lên
                </button>
            </div>
        </form>
    </div>
</div>

<!-- BỘ SƯU TẬP ẢNH HIỆN CÓ -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h2 class="h6 fw-bold mb-3">Bộ sưu tập hình ảnh hiện có (<?= count($images) ?>)</h2>

        <?php if (!empty($images)): ?>
            <div class="row g-3">
                <?php foreach ($images as $idx => $img): ?>
                    <?php
                    $imgPath = is_array($img) ? ($img['image_path'] ?? '') : (string) $img;
                    $isPrimary = is_array($img) ? ((int) ($img['is_primary'] ?? 0) === 1) : ($idx === 0);
                    ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card h-100 <?= $isPrimary ? 'border-danger border-2' : 'border' ?>">
                            <div class="ui-style-16 p-3 text-center bg-light">
                                <img
                                    src="<?= SecurityHelper::productImage($imgPath) ?>"
                                    alt="Ảnh sản phẩm"
                                    style="max-height: 160px; max-width: 100%; object-fit: contain;"
                                >
                            </div>
                            <div class="card-body p-2 text-center">
                                <?php if ($isPrimary): ?>
                                    <span class="badge bg-danger mb-2">Ảnh đại diện chính</span>
                                <?php else: ?>
                                    <form data-mvc-form="true" action="<?= SecurityHelper::url('/admin/products/' . $productId . '/images/primary') ?>" method="POST" class="d-inline">
                                        <?= SecurityHelper::csrfField() ?>
                                        <input type="hidden" name="image_path" value="<?= SecurityHelper::e($imgPath) ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-primary mb-1">Đặt làm ảnh chính</button>
                                    </form>
                                <?php endif; ?>

                                <form data-mvc-form="true" action="<?= SecurityHelper::url('/admin/products/' . $productId . '/images/delete') ?>" method="POST" class="d-inline">
                                    <?= SecurityHelper::csrfField() ?>
                                    <input type="hidden" name="image_path" value="<?= SecurityHelper::e($imgPath) ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger mb-1" onclick="return confirm('Xóa hình ảnh này?');">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted small mb-0">Sản phẩm này chưa có hình ảnh nào.</p>
        <?php endif; ?>
    </div>
</div>
