<?php
use App\Helpers\SecurityHelper;
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

    <!-- Danh sách đơn hàng -->
    <div class="col-md-8 col-lg-9">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h1 class="h5 fw-bold mb-0"><i class="bi bi-bag-check text-danger me-2"></i>Lịch sử đơn hàng</h1>
                <span class="small text-muted">Tổng cộng: <strong><?= (int) $totalItems ?></strong> đơn</span>
            </div>

            <?php if (!empty($orders)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th class="text-end">Tổng tiền</th>
                                <th>Thanh toán</th>
                                <th>Trạng thái đơn</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <?php
                                $orderBadge   = SecurityHelper::orderStatusBadge($order['status'] ?? 'pending');
                                $paymentBadge = SecurityHelper::paymentStatusBadge($order['payment_status'] ?? 'unpaid');
                                $methodText   = ($order['payment_method'] ?? 'cod') === 'qr_simulation' ? 'QR mô phỏng' : 'COD';
                                ?>
                                <tr>
                                    <td class="fw-bold text-danger">#<?= SecurityHelper::e($order['order_code']) ?></td>
                                    <td class="small text-muted">
                                        <?= SecurityHelper::e(date('d/m/Y H:i', strtotime($order['created_at']))) ?>
                                    </td>
                                    <td class="text-end fw-bold"><?= SecurityHelper::formatPrice($order['total_amount']) ?></td>
                                    <td>
                                        <div class="small text-muted mb-1"><?= SecurityHelper::e($methodText) ?></div>
                                        <span class="badge <?= SecurityHelper::e($paymentBadge['class']) ?>">
                                            <?= SecurityHelper::e($paymentBadge['label']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?= SecurityHelper::e($orderBadge['class']) ?>">
                                            <?= SecurityHelper::e($orderBadge['label']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= SecurityHelper::url('/orders/' . (int) $order['id']) ?>" class="btn btn-sm btn-outline-dark">
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalPages > 1): ?>
                    <div class="card-footer bg-white py-3 d-flex justify-content-center">
                        <nav aria-label="Phân trang đơn hàng">
                            <ul class="pagination pagination-sm mb-0">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                        <a class="page-link <?= $i === $currentPage ? 'bg-danger border-danger' : 'text-danger' ?>"
                                           href="<?= SecurityHelper::url('/orders?page=' . $i) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="card-body text-center p-5">
                    <i class="bi bi-bag-x fs-1 text-muted mb-3"></i>
                    <h2 class="h6 fw-bold">Bạn chưa có đơn hàng nào</h2>
                    <p class="text-muted small mb-3">Hãy khám phá các dòng điện thoại mới nhất tại DTD STORE.</p>
                    <a href="<?= SecurityHelper::url('/products') ?>" class="btn btn-dtd btn-sm px-4">Mua sắm ngay</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
