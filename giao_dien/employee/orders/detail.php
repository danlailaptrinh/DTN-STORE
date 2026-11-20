<?php
use App\Helpers\SecurityHelper;

$orderBadge   = SecurityHelper::orderStatusBadge($order['status'] ?? 'pending');
$paymentBadge = SecurityHelper::paymentStatusBadge($order['payment_status'] ?? 'unpaid');
$methodLabel  = ($order['payment_method'] ?? 'cod') === 'qr_simulation'
    ? 'Chuyển khoản QR mô phỏng'
    : 'Thanh toán khi nhận hàng (COD)';

$statusLabels = [
    'pending'   => 'Chờ xác nhận',
    'confirmed' => 'Đã xác nhận',
    'shipping'  => 'Đang giao hàng',
    'delivered' => 'Đã giao hàng',
    'cancelled' => 'Đã hủy',
];

$employeeAllowedMap = [
    'pending'   => ['confirmed', 'shipping', 'delivered'],
    'confirmed' => ['shipping', 'delivered'],
    'shipping'  => ['delivered'],
    'delivered' => [],
    'cancelled' => [],
];
$allowedNextStatuses = $employeeAllowedMap[$order['status']] ?? [];
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <a href="<?= SecurityHelper::url('/employee/orders') ?>" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách đơn được giao
        </a>
        <h1 class="h4 fw-bold mb-0 mt-1">Xử lý đơn hàng #<?= SecurityHelper::e($order['order_code']) ?></h1>
        <div class="small text-muted">
            Được giao lúc: <?= SecurityHelper::e(($order['assigned_at'] ? date('d/m/Y H:i', strtotime($order['assigned_at'])) : 'Chưa phân công')) ?>
            bởi <?= SecurityHelper::e($order['assigned_by_name']) ?>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <span class="badge <?= SecurityHelper::e($orderBadge['class']) ?> px-3 py-2">
            Đơn hàng: <?= SecurityHelper::e($orderBadge['label']) ?>
        </span>
        <span class="badge <?= SecurityHelper::e($paymentBadge['class']) ?> px-3 py-2">
            Thanh toán: <?= SecurityHelper::e($paymentBadge['label']) ?>
        </span>
    </div>
</div>

<div class="row g-4">
    <!-- Cột trái: Thông tin giao hàng & Sản phẩm -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h2 class="h6 fw-bold mb-0"><i class="bi bi-geo-alt text-primary me-2"></i>Thông tin giao hàng</h2>
            </div>
            <div class="card-body small">
                <div class="row g-2">
                    <div class="col-md-6"><strong>Người nhận:</strong> <?= SecurityHelper::e($order['receiver_name']) ?></div>
                    <div class="col-md-6"><strong>Số điện thoại:</strong> <?= SecurityHelper::e($order['receiver_phone']) ?></div>
                    <div class="col-12"><strong>Địa chỉ:</strong> <?= SecurityHelper::e($order['shipping_address']) ?></div>
                    <?php if (!empty($order['note'])): ?>
                        <div class="col-12"><strong>Ghi chú khách hàng:</strong> <?= SecurityHelper::e($order['note']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($order['assignment_note'])): ?>
                        <div class="col-12"><strong>Ghi chú phân công từ Admin:</strong> <?= SecurityHelper::e($order['assignment_note']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h2 class="h6 fw-bold mb-0"><i class="bi bi-phone text-primary me-2"></i>Sản phẩm cần giao</h2>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-center">SL</th>
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
                                            style="width: 52px; height: 52px; object-fit: contain;"
                                        >
                                        <div>
                                            <div class="fw-semibold"><?= SecurityHelper::e($item['product_name']) ?></div>
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
                            <td colspan="3" class="text-end fw-bold">Tổng giá trị đơn hàng:</td>
                            <td class="text-end fs-5 fw-bold text-danger"><?= SecurityHelper::formatPrice($order['total_amount']) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Cột phải: Cập nhật tiến độ xử lý & Thông tin thanh toán (chỉ xem) -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h2 class="h6 fw-bold mb-0"><i class="bi bi-clipboard-check text-primary me-2"></i>Cập nhật tiến độ xử lý</h2>
            </div>
            <div class="card-body">
                <div class="small mb-3">
                    Trạng thái hiện tại:
                    <span class="badge <?= SecurityHelper::e($orderBadge['class']) ?> ms-1">
                        <?= SecurityHelper::e($orderBadge['label']) ?>
                    </span>
                </div>

                <?php if (!empty($allowedNextStatuses)): ?>
                    <form action="<?= SecurityHelper::url('/employee/orders/' . (int) $order['id'] . '/status') ?>" method="POST">
                        <?= SecurityHelper::csrfField() ?>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Chuyển tiến độ sang</label>
                            <select name="status" class="form-select">
                                <?php foreach ($allowedNextStatuses as $st): ?>
                                    <option value="<?= SecurityHelper::e($st) ?>">
                                        <?= SecurityHelper::e($statusLabels[$st] ?? $st) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-sm">
                            Cập nhật trạng thái đơn
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-light border small mb-0">
                        Đơn hàng đang ở trạng thái <strong><?= SecurityHelper::e($orderBadge['label']) ?></strong> nên không cần cập nhật thêm.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h2 class="h6 fw-bold mb-0"><i class="bi bi-credit-card text-primary me-2"></i>Thông tin thanh toán (Chỉ xem)</h2>
            </div>
            <div class="card-body small">
                <div class="mb-2"><strong>Phương thức:</strong> <?= SecurityHelper::e($methodLabel) ?></div>
                <div class="mb-2">
                    <strong>Trạng thái:</strong>
                    <span class="badge <?= SecurityHelper::e($paymentBadge['class']) ?> ms-1">
                        <?= SecurityHelper::e($paymentBadge['label']) ?>
                    </span>
                </div>
                <?php if (!empty($order['transfer_content'])): ?>
                    <div class="mb-2"><strong>Nội dung CK:</strong> <span class="font-monospace"><?= SecurityHelper::e($order['transfer_content']) ?></span></div>
                <?php endif; ?>
                <?php if ($order['payment_method'] === 'cod' && $order['payment_status'] === 'unpaid'): ?>
                    <div class="alert alert-warning mb-0 mt-2">
                        <i class="bi bi-cash-coin me-1"></i>
                        Đơn COD chưa thu tiền: cần thu <strong><?= SecurityHelper::formatPrice($order['total_amount']) ?></strong> khi giao hàng (Admin sẽ xác nhận thanh toán COD).
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
