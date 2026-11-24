<?php

namespace App\Helpers;

class SecurityHelper
{
    // Chống XSS khi hiển thị dữ liệu
    public static function e($value): string
    {
        return htmlspecialchars(
            (string) ($value ?? ''),
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    }

    // Làm sạch chuỗi đầu vào
    public static function cleanInput(?string $value): string
    {
        return trim((string) ($value ?? ''));
    }

    // Tạo đường dẫn Router Admin hoặc Client
    public static function url(string $route = '/admin'): string
    {
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '', 2), '/\\');

        if ($route === '/' || !str_starts_with($route, '/admin')) {
            return ($base === '' ? '' : $base) . '/index.php?route=' . rawurlencode($route);
        }

        $parts = explode('?', $route, 2);
        $path = $parts[0];
        $query = isset($parts[1]) ? '&' . $parts[1] : '';

        return ($base === '' ? '' : $base)
            . '/admin/index.php?route='
            . rawurlencode($path)
            . $query;
    }

    // Lấy đường dẫn CSS, JS, hình ảnh
    public static function asset(string $path): string
    {
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '', 2), '/\\');

        return ($base === '' ? '' : $base)
            . '/assets/'
            . ltrim($path, '/');
    }

    // Hiển thị ảnh sản phẩm
    public static function productImage(?string $path): string
    {
        $path = ltrim((string) $path, '/');

        if (str_starts_with($path, '[') && str_ends_with($path, ']')) {
            $arr = json_decode($path, true);
            if (is_array($arr) && !empty($arr)) {
                $path = ltrim((string) $arr[0], '/');
            }
        }

        if ($path === '' || str_contains($path, '..')) {
            return self::asset('images/no-image.svg');
        }

        $root = dirname(__DIR__, 2);

        if (is_file($root . '/' . $path)) {
            $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '', 2), '/\\');
            return ($base === '' ? '' : $base) . '/' . $path;
        }

        if (is_file($root . '/assets/images/' . $path)) {
            return self::asset('images/' . $path);
        }

        if (is_file($root . '/assets/images/products/' . $path)) {
            return self::asset('images/products/' . $path);
        }

        if (is_file($root . '/assets/uploads/products/' . $path)) {
            return self::asset('uploads/products/' . $path);
        }

        return self::asset('images/no-image.svg');
    }

    // Định dạng giá tiền
    public static function formatPrice($price): string
    {
        return number_format(
            (float) $price,
            0,
            ',',
            '.'
        ) . '₫';
    }

    // Tạo CSRF Token
    public static function csrfToken(): string
    {
        SessionHelper::start();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(
                random_bytes(32)
            );
        }

        return $_SESSION['csrf_token'];
    }

    // Tạo input CSRF cho form POST
    public static function csrfField(): string
    {
        return '<input type="hidden" name="csrf_token" value="'
            . self::e(self::csrfToken())
            . '">';
    }

    // Kiểm tra CSRF Token
    public static function verifyCsrfToken(?string $token): bool
    {
        SessionHelper::start();

        $saved = $_SESSION['csrf_token'] ?? '';

        return is_string($token)
            && $saved !== ''
            && hash_equals($saved, $token);
    }

    // Badge trạng thái đơn hàng
    public static function orderStatusBadge(string $status): array
    {
        return match ($status) {
            'pending'   => ['label' => 'Chờ xác nhận',  'class' => 'bg-warning text-dark'],
            'confirmed' => ['label' => 'Đã xác nhận',   'class' => 'bg-info text-dark'],
            'shipping'  => ['label' => 'Đang giao hàng', 'class' => 'bg-primary'],
            'delivered' => ['label' => 'Đã giao hàng',  'class' => 'bg-success'],
            'cancelled' => ['label' => 'Đã hủy',        'class' => 'bg-danger'],
            default     => ['label' => 'Không xác định', 'class' => 'bg-secondary'],
        };
    }

    // Badge trạng thái thanh toán
    public static function paymentStatusBadge(string $paymentStatus): array
    {
        return match ($paymentStatus) {
            'unpaid'              => ['label' => 'Chưa thanh toán',                 'class' => 'bg-secondary'],
            'paid'                => ['label' => 'Đã thanh toán (COD)',             'class' => 'bg-success'],
            'simulated_confirmed' => ['label' => 'Đã xác nhận thanh toán mô phỏng', 'class' => 'bg-info text-dark'],
            default               => ['label' => 'Chưa thanh toán',                 'class' => 'bg-secondary'],
        };
    }

    // Hiển thị sao đánh giá
    public static function stars(float|int $rating): string
    {
        $rating = max(1, min(5, (int) round($rating)));
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            $html .= '<i class="bi ' . ($i <= $rating ? 'bi-star-fill text-warning' : 'bi-star text-muted') . '"></i>';
        }
        return $html;
    }
}
