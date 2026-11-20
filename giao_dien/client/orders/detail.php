<?php
use App\Helpers\SecurityHelper;

$orderBadge   = SecurityHelper::orderStatusBadge($order['status'] ?? 'pending');
$paymentBadge = SecurityHelper::paymentStatusBadge($order['payment_status'] ?? 'unpaid');
$methodLabel  = ($order['payment_method'] ?? 'cod') === 'qr_simulation'
    ? 'Chuyển khoản QR mô phỏng'
    : 'Thanh toán khi nhận hàng (COD)';
?>

<div class="row g-4">
    <!-- Menu tài khoản bên trái -->
    <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="list-group list-group-flush">
                <a href="<?= SecurityHelper::url('/account') ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-person me-2"></i>Thông tin cá nhân
                </a>
                <a href="<?= SecurityHelper::url('/account/edit') ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-pencil-square me-2"></i>Cập nhật hồ sơ &amp; Mật khẩu
                </a>
                <a href="<?= SecurityHelper::url('/orders') ?>" class="list-group-item list-group-item-action active bg-danger border-danger">
                    <i class="bi bi-bag-check me-2"></i>Lịch sử đơn hàng
                </a>
            </div>
        </div>
    </div>

    <!-- Nội dung chi tiết đơn hàng -->
    <div class="col-md-8 col-lg-9">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <a href="<?= SecurityHelper::url('/orders') ?>" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left me-1"></i>Quay lại lịch sử đơn hàng
                </a>
                <h1 class="h4 fw-bold mb-0 mt-1">Đơn hàng #<?= SecurityHelper::e($order['order_code']) ?></h1>
                <div class="small text-muted">
                    Đặt lúc: <?= SecurityHelper::e(date('d/m/Y H:i:s', strtotime($order['created_at']))) ?>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <?php if (($order['payment_method'] ?? '') === 'qr_simulation' && ($order['payment_status'] ?? '') === 'unpaid' && ($order['status'] ?? '') !== 'cancelled'): ?>
                    <a href="<?= SecurityHelper::url('/orders/' . (int) $order['id'] . '/qr') ?>" class="btn btn-dtd btn-sm">
                        <i class="bi bi-qr-code-scan me-1"></i>Thanh toán QR
                    </a>
                <?php endif; ?>

                <?php if (($order['status'] ?? '') === 'pending' && ($order['payment_status'] ?? 'unpaid') === 'unpaid'): ?>
                    <form action="<?= SecurityHelper::url('/orders/' . (int) $order['id'] . '/cancel') ?>"
                          method="POST"
                          onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                        <?= SecurityHelper::csrfField() ?>
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-x-circle me-1"></i>Hủy đơn hàng
                        </button>
                    </form>
                <?php elseif (($order['status'] ?? '') === 'pending'): ?>
                    <span class="badge bg-light text-muted border d-inline-flex align-items-center px-3 py-2">
                        <i class="bi bi-shield-check text-info me-1"></i>Đã xác nhận thanh toán (liên hệ cửa hàng nếu cần hủy)
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Trạng thái đơn hàng & Trạng thái thanh toán tách biệt -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h6 fw-bold text-uppercase text-muted mb-3">
                            <i class="bi bi-geo-alt text-danger me-2"></i>Thông tin giao hàng
                        </h2>
                        <div class="small mb-1"><strong>Người nhận:</strong> <?= SecurityHelper::e($order['receiver_name']) ?></div>
                        <div class="small mb-1"><strong>Số điện thoại:</strong> <?= SecurityHelper::e($order['receiver_phone']) ?></div>
                        <div class="small mb-1"><strong>Địa chỉ:</strong> <?= SecurityHelper::e($order['shipping_address']) ?></div>
                        <?php if (!empty($order['note'])): ?>
                            <div class="small mb-2"><strong>Ghi chú:</strong> <?= SecurityHelper::e($order['note']) ?></div>
                        <?php endif; ?>
                        <div class="mt-2 pt-2 border-top small">
                            <span class="text-muted me-2">Trạng thái xử lý đơn:</span>
                            <span class="badge <?= SecurityHelper::e($orderBadge['class']) ?>">
                                <?= SecurityHelper::e($orderBadge['label']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h6 fw-bold text-uppercase text-muted mb-3">
                            <i class="bi bi-credit-card text-danger me-2"></i>Thông tin thanh toán
                        </h2>
                        <div class="small mb-1"><strong>Phương thức:</strong> <?= SecurityHelper::e($methodLabel) ?></div>
                        <div class="small mb-1">
                            <strong>Trạng thái thanh toán:</strong>
                            <span class="badge <?= SecurityHelper::e($paymentBadge['class']) ?> ms-1">
                                <?= SecurityHelper::e($paymentBadge['label']) ?>
                            </span>
                        </div>
                        <?php if (!empty($order['transfer_content'])): ?>
                            <div class="small mb-1">
                                <strong>Nội dung CK:</strong>
                                <span class="font-monospace"><?= SecurityHelper::e($order['transfer_content']) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($order['payment_confirmed_at'])): ?>
                            <div class="small mb-1">
                                <strong>Xác nhận lúc:</strong>
                                <?= SecurityHelper::e(date('d/m/Y H:i:s', strtotime($order['payment_confirmed_at']))) ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($order['payment_note'])): ?>
                            <div class="small text-muted mt-2 pt-2 border-top">
                                <?= SecurityHelper::e($order['payment_note']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm snapshot tại thời điểm mua -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h2 class="h6 fw-bold mb-0"><i class="bi bi-phone text-danger me-2"></i>Sản phẩm đã đặt (dữ liệu lưu tại thời điểm mua)</h2>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($details as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img
                                            src="<?= SecurityHelper::productImage($item['product_image']) ?>"
                                            alt="<?= SecurityHelper::e($item['product_name']) ?>"
                                            class="border rounded p-1 bg-white"
                                            style="width: 56px; height: 56px; object-fit: contain;"
                                        >
                                        <div>
                                            <?php if (!empty($item['product_slug'])): ?>
                                                <a href="<?= SecurityHelper::url('/product/' . $item['product_slug']) ?>" class="fw-semibold text-dark text-decoration-none d-block">
                                                    <?= SecurityHelper::e($item['product_name']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="fw-semibold text-dark d-block">
                                                    <?= SecurityHelper::e($item['product_name']) ?>
                                                </span>
                                            <?php endif; ?>
                                            <div class="small text-muted">
                                                <?= SecurityHelper::e($item['category_name']) ?> •
                                                RAM <?= SecurityHelper::e($item['ram']) ?> •
                                                <?= SecurityHelper::e($item['storage']) ?> •
                                                Màu <?= SecurityHelper::e($item['color']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end"><?= SecurityHelper::formatPrice($item['unit_price']) ?></td>
                                <td class="text-center"><?= (int) $item['quantity'] ?></td>
                                <td class="text-end fw-bold text-danger"><?= SecurityHelper::formatPrice($item['subtotal']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Tổng thanh toán:</td>
                            <td class="text-end fs-5 fw-bold text-danger"><?= SecurityHelper::formatPrice($order['total_amount']) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
