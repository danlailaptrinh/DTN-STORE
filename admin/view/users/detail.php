<?php
use App\Helpers\SecurityHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold mb-0">Chi tiết tài khoản #<?= (int) $targetUser['id'] ?></h1>
    <a href="<?= SecurityHelper::url('/admin/users') ?>" class="btn btn-outline-secondary btn-sm">Quay lại danh sách</a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3">Thông tin người dùng</h2>
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="ui-style-13 text-muted">Họ và tên:</th>
                        <td class="fw-semibold"><?= SecurityHelper::e($targetUser['full_name']) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Email:</th>
                        <td><?= SecurityHelper::e($targetUser['email']) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Số điện thoại:</th>
                        <td><?= SecurityHelper::e($targetUser['phone'] ?: 'Chưa cập nhật') ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Địa chỉ:</th>
                        <td><?= SecurityHelper::e($targetUser['address'] ?: 'Chưa cập nhật') ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Vai trò hiện tại:</th>
                        <td><span class="badge bg-primary"><?= SecurityHelper::e(strtoupper($targetUser['role_name'])) ?></span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Trạng thái:</th>
                        <td>
                            <?= (int) $targetUser['status'] === 1
                                ? '<span class="badge bg-success">Đang hoạt động</span>'
                                : '<span class="badge bg-dark">Đã khóa</span>' ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Ngày tạo:</th>
                        <td><?= SecurityHelper::e(date('d/m/Y H:i', strtotime($targetUser['created_at']))) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3">Cập nhật phân quyền</h2>
                <?php if ((int) $targetUser['id'] !== (int) $currentUser['id']): ?>
                    <form action="<?= SecurityHelper::url('/admin/users/update-role') ?>" method="POST">
                        <?= SecurityHelper::csrfField() ?>
                        <input type="hidden" name="user_id" value="<?= (int) $targetUser['id'] ?>">

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Chọn vai trò mới</label>
                            <select name="role_id" class="form-select">
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= (int) $r['id'] ?>" <?= (int) $targetUser['role_id'] === (int) $r['id'] ? 'selected' : '' ?>>
                                        <?= SecurityHelper::e(strtoupper($r['name'])) ?> - <?= SecurityHelper::e($r['description']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-dtd w-100">Cập nhật vai trò</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-info small mb-0">
                        Bạn đang xem tài khoản Admin của chính mình nên không thể tự thay đổi quyền hoặc tự khóa.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
