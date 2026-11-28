<?php

use App\Helpers\SecurityHelper;
use App\Helpers\SessionHelper;

require_once __DIR__ . '/../admin/helpers/SessionHelper.php';
require_once __DIR__ . '/../admin/helpers/SecurityHelper.php';

SessionHelper::start();

if (!defined('APP_NAME')) {
    define('APP_NAME', 'DTD STORE');
}

if (!function_exists('viewE')) {
    function viewE($value): string
    {
        return SecurityHelper::e($value);
    }
}

if (!function_exists('viewUrl')) {
    function viewUrl(string $route = '/'): string
    {
        return SecurityHelper::url($route);
    }
}

if (!function_exists('viewAsset')) {
    function viewAsset(string $path): string
    {
        return SecurityHelper::asset($path);
    }
}

if (!function_exists('viewImage')) {
    function viewImage(?string $path): string
    {
        return SecurityHelper::productImage($path);
    }
}

if (!function_exists('viewPrice')) {
    function viewPrice($amount): string
    {
        return SecurityHelper::formatPrice($amount);
    }
}

if (!function_exists('viewCsrfToken')) {
    function viewCsrfToken(): string
    {
        return SecurityHelper::getCsrfToken();
    }
}

if (!function_exists('viewCsrfField')) {
    function viewCsrfField(): string
    {
        return SecurityHelper::csrfField();
    }
}

if (!function_exists('viewGetUser')) {
    function viewGetUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }
}

if (!function_exists('viewGetFlash')) {
    function viewGetFlash(): ?array
    {
        return SessionHelper::getFlash();
    }
}

if (!function_exists('viewCartCount')) {
    function viewCartCount(): int
    {
        if (empty($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            return 0;
        }

        return array_sum(array_map('intval', $_SESSION['cart']));
    }
}

if (!function_exists('viewOrderStatusBadge')) {
    function viewOrderStatusBadge(string $status): array
    {
        return match ($status) {
            'pending' => ['label' => 'Chờ xác nhận', 'class' => 'bg-warning text-dark'],
            'confirmed' => ['label' => 'Đã xác nhận', 'class' => 'bg-info text-dark'],
            'shipping' => ['label' => 'Đang giao', 'class' => 'bg-primary'],
            'delivered' => ['label' => 'Đã giao', 'class' => 'bg-success'],
            'cancelled' => ['label' => 'Đã hủy', 'class' => 'bg-danger'],
            default => ['label' => $status, 'class' => 'bg-secondary'],
        };
    }
}

if (!function_exists('viewPaymentStatusBadge')) {
    function viewPaymentStatusBadge(string $status): array
    {
        return match ($status) {
            'paid' => ['label' => 'Đã thanh toán', 'class' => 'bg-success'],
            'pending' => ['label' => 'Chờ thanh toán', 'class' => 'bg-warning text-dark'],
            'failed' => ['label' => 'Thất bại', 'class' => 'bg-danger'],
            default => ['label' => $status, 'class' => 'bg-secondary'],
        };
    }
}
