<?php

/**
 * Lớp hỗ trợ bảo mật (XSS, CSRF), định dạng dữ liệu và URL cho giao diện Demo
 */

namespace App\Helpers;

class SecurityHelper
{
    public static function baseUrl(): string
    {
        $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($scriptName === '' || $scriptName[0] !== '/') {
            return '';
        }

        $dir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        $dir = preg_replace('#/(admin|employee|demo)$#', '', $dir) ?? $dir;

        return $dir === '/' ? '' : $dir;
    }

    public static function e(mixed $value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function cleanInput(?string $value): string
    {
        return trim((string) ($value ?? ''));
    }

    public static function csrfToken(): string
    {
        SessionHelper::start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function csrfField(): string
    {
        return '';
    }

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
     * Tạo đường dẫn điều hướng nội bộ trong Demo (luôn qua demo/index.php)
     */
    public static function url(string $path = '/'): string
    {
        $parts = explode('?', $path, 2);
        $routePath = $parts[0] === '' ? '/' : $parts[0];
        $query = isset($parts[1]) && $parts[1] !== '' ? '&' . $parts[1] : '';

        return self::baseUrl() . '/demo/index.php?route=' . rawurlencode($routePath) . $query;
    }

    /**
     * Tạo đường dẫn tới file tĩnh trong assets
     */
    public static function asset(string $path): string
    {
        return self::baseUrl() . '/assets/' . ltrim($path, '/');
    }

    /**
     * Lấy đường dẫn ảnh sản phẩm an toàn trong Demo
     */
    public static function productImage(?string $path): string
    {
        $path = ltrim((string) $path, '/');
        $root = dirname(__DIR__);
        if ($path !== '' && !str_contains($path, '..') && is_file($root . '/' . $path)) {
            return self::baseUrl() . '/' . $path;
        }

        return self::baseUrl() . '/assets/images/no-image.svg';
    }

    public static function formatPrice(float|int|string $amount): string
    {
        return number_format((float) $amount, 0, ',', '.') . '₫';
    }

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

    public static function paymentStatusBadge(string $paymentStatus): array
    {
        return match ($paymentStatus) {
            'unpaid'              => ['label' => 'Chưa thanh toán',                 'class' => 'bg-secondary'],
            'paid'                => ['label' => 'Đã thanh toán (COD)',             'class' => 'bg-success'],
            'simulated_confirmed' => ['label' => 'Đã xác nhận thanh toán mô phỏng', 'class' => 'bg-info text-dark'],
            default               => ['label' => 'Không xác định',                  'class' => 'bg-dark'],
        };
    }
}
