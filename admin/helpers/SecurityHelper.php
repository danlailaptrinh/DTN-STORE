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

    // Tạo đường dẫn Router Admin
    public static function url(string $route = '/admin'): string
    {
        $base = dirname($_SERVER['SCRIPT_NAME'], 2);

        return rtrim($base, '/')
            . '/admin/index.php?route='
            . rawurlencode($route);
    }

    // Lấy đường dẫn CSS, JS, hình ảnh
    public static function asset(string $path): string
    {
        $base = dirname($_SERVER['SCRIPT_NAME'], 2);

        return rtrim($base, '/')
            . '/assets/'
            . ltrim($path, '/');
    }

    // Hiển thị ảnh sản phẩm
    public static function productImage(?string $path): string
    {
        $path = ltrim((string) $path, '/');

        // Ngăn đường dẫn bất hợp lệ
        if (
            $path === '' ||
            str_contains($path, '..') ||
            !preg_match(
                '~^(assets/|uploads/)[a-zA-Z0-9/_(). -]+\.(jpg|jpeg|png|webp|gif)$~i',
                $path
            )
        ) {
            return self::asset('images/no-image.svg');
        }

        $root = dirname(__DIR__, 2);

        if (!is_file($root . '/' . $path)) {
            return self::asset('images/no-image.svg');
        }

        $base = dirname($_SERVER['SCRIPT_NAME'], 2);

        return rtrim($base, '/') . '/' . $path;
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
}
