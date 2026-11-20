<?php
use App\Helpers\SecurityHelper;

$orderBadge   = SecurityHelper::orderStatusBadge($order['status'] ?? 'pending');
$paymentBadge = SecurityHelper::paymentStatusBadge($order['payment_status'] ?? 'unpaid');
$methodLabel  = ($order['payment_method'] ?? 'cod') === 'qr_simulation'
    ? 'Chuyển khoản QR mô phỏng'
    : 'Thanh toán khi nhận hàng (COD)';
?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm text-center p-4 p-md-5">
            <div class="mb-3">
                <span class="ui-style-4 d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-lg fs-1"></i>
                </span>
            </div>

            <h1 class="h4 fw-bold mb-2">Đặt hàng thành công!</h1>
            <p class="text-muted mb-4">
                Cảm ơn bạn đã tin tưởng mua sắm tại <strong><?= SecurityHelper::e(APP_NAME) ?></strong>.
                Đơn hàng của bạn đã được hệ thống tiếp nhận.
            </p>

            <div class="bg-light rounded-3 p-3 p-md-4 text-start mb-4">
                <div class="row g-2 small">
                    <div class="col-6 text-muted">Mã đơn hàng:</div>
                    <div class="col-6 fw-bold text-end text-danger">#<?= SecurityHelper::e($order['order_code']) ?></div>

                    <div class="col-6 text-muted">Người nhận:</div>
                    <div class="col-6 fw-semibold text-end"><?= SecurityHelper::e($order['receiver_name']) ?></div>

                    <div class="col-6 text-muted">Số điện thoại:</div>
                    <div class="col-6 text-end"><?= SecurityHelper::e($order['receiver_phone']) ?></div>

                    <div class="col-6 text-muted">Tổng thanh toán:</div>
                    <div class="col-6 fw-bold text-danger text-end"><?= SecurityHelper::formatPrice($order['total_amount']) ?></div>

                    <div class="col-6 text-muted">Phương thức thanh toán:</div>
                    <div class="col-6 text-end"><?= SecurityHelper::e($methodLabel) ?></div>

                    <div class="col-6 text-muted">Trạng thái đơn hàng:</div>
                    <div class="col-6 text-end">
                        <span class="badge <?= SecurityHelper::e($orderBadge['class']) ?>">
                            <?= SecurityHelper::e($orderBadge['label']) ?>
                        </span>
                    </div>

                    <div class="col-6 text-muted">Trạng thái thanh toán:</div>
                    <div class="col-6 text-end">
                        <span class="badge <?= SecurityHelper::e($paymentBadge['class']) ?>">
                            <?= SecurityHelper::e($paymentBadge['label']) ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap justify-content-center gap-2">
                <?php if (($order['payment_method'] ?? '') === 'qr_simulation' && ($order['payment_status'] ?? '') === 'unpaid' && ($order['status'] ?? '') !== 'cancelled'): ?>
                    <a href="<?= SecurityHelper::url('/orders/' . (int) $order['id'] . '/qr') ?>" class="btn btn-dtd">
                        <i class="bi bi-qr-code-scan me-1"></i>Mở lại mã QR thanh toán
                    </a>
                <?php endif; ?>

                <a href="<?= SecurityHelper::url('/orders/' . (int) $order['id']) ?>" class="btn btn-dark">
                    <i class="bi bi-receipt me-1"></i>Xem chi tiết đơn hàng
                </a>
                <a href="<?= SecurityHelper::url('/products') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-phone me-1"></i>Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</div>
