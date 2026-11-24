<?php
/**
 * Lớp hỗ trợ bảo mật (XSS, CSRF), định dạng dữ liệu và URL
 * File: app/helpers/SecurityHelper.php
 */

namespace App\Helpers;

class SecurityHelper
{
    /**
     * Mã hóa HTML để chống XSS khi xuất dữ liệu ra View
     */
    public static function e(mixed $value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Làm sạch chuỗi đầu vào từ form
     */
    public static function cleanInput(?string $value): string
    {
        return trim((string) ($value ?? ''));
    }

    /**
     * Tạo hoặc lấy CSRF Token trong Session
     */
    public static function csrfToken(): string
    {
        SessionHelper::start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Tạo thẻ input ẩn chứa CSRF Token để nhúng vào form POST
     */
    public static function csrfField(): string { return ''; }

    /**
     * Kiểm tra CSRF Token gửi lên từ form
     */
    public static function verifyCsrfToken(?string $token): bool
    {
        SessionHelper::start();
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        if ($sessionToken === '' || empty($token)) {
            return false;
        }
        return hash_equals($sessionToken, $token);
    }

    /**
     * Tạo đường dẫn đầy đủ dựa trên BASE_URL
     */
    public static function url(string $path = '/'): string {
        return $GLOBALS['prefix'].'index.php?route='.rawurlencode(explode('?', $path)[0]);
    }
    /**
     * Tạo đường dẫn tới file tĩnh trong public/assets
     */
    public static function asset(string $path): string { return '/assets/'.ltrim($path,'/'); }
    /**
     * Lấy đường dẫn ảnh sản phẩm an toàn (fallback về ảnh mặc định nếu file chưa tồn tại)
     */
    public static function productImage(?string $path): string { return '/'.($path && file_exists(dirname(__DIR__).'/'.$path)?$path:'assets/images/no-image.svg'); }
    /**
     * Định dạng tiền tệ VNĐ
     */
    public static function formatPrice(float|int|string $amount): string
    {
        return number_format((float) $amount, 0, ',', '.') . '₫';
    }

    /**
     * Nhãn và màu badge cho 5 trạng thái đơn hàng
     */
    public static function orderStatusBadge(string $status): array
    {
        return match ($status) {
            'pending'   => ['label' => 'Chờ xác nhận',  'class' => 'bg-warning text-dark'],
            'confirmed' => ['label' => 'Đã xác nhận',   'class' => 'bg-info text-dark'],
            'shipping'  => ['label' => 'Đang giao hàng','class' => 'bg-primary'],
            'delivered' => ['label' => 'Đã giao hàng',  'class' => 'bg-success'],
            'cancelled' => ['label' => 'Đã hủy',        'class' => 'bg-danger'],
            default     => ['label' => 'Không xác định','class' => 'bg-secondary'],
        };
    }

    /**
     * Nhãn và màu badge cho trạng thái thanh toán (độc lập với trạng thái đơn hàng)
     */
    public static function paymentStatusBadge(string $paymentStatus): array
    {
        return match ($paymentStatus) {
            'unpaid'              => ['label' => 'Chưa thanh toán',                  'class' => 'bg-secondary'],
            'paid'                => ['label' => 'Đã thanh toán (COD)',              'class' => 'bg-success'],
            'simulated_confirmed' => ['label' => 'Đã xác nhận thanh toán mô phỏng',  'class' => 'bg-info text-dark'],
            default               => ['label' => 'Không xác định',                   'class' => 'bg-dark'],
        };
    }
}
