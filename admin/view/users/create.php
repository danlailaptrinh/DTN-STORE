<?php
use App\Helpers\SecurityHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold mb-0">Tạo tài khoản nhân viên / người dùng mới</h1>
    <a href="<?= SecurityHelper::url('/admin/users') ?>" class="btn btn-outline-secondary btn-sm">Quay lại</a>
</div>

<div class="ui-style-14 card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="<?= SecurityHelper::url('/admin/users/store') ?>" method="POST">
            <?= SecurityHelper::csrfField() ?>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Địa chỉ</label>
                <input type="text" name="address" class="form-control">
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" minlength="6" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Vai trò phân quyền <span class="text-danger">*</span></label>
                    <select name="role_id" class="form-select" required>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= (int) $r['id'] ?>" <?= $r['name'] === 'employee' ? 'selected' : '' ?>>
                                <?= SecurityHelper::e(strtoupper($r['name'])) ?> - <?= SecurityHelper::e($r['description']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-dtd px-4">Lưu tài khoản</button>
        </form>
    </div>
</div>
