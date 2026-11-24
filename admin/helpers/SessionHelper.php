<?php

namespace App\Helpers;

class SessionHelper
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setFlash(
        string $type,
        string $message
    ): void {
        self::start();

        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    public static function getFlash(): ?array
    {
        self::start();

        $flash = $_SESSION['flash'] ?? null;

        unset($_SESSION['flash']);

        return $flash;
    }

    public static function getUser(): ?array
    {
        self::start();

        return $_SESSION['user'] ?? [
            'id' => 1,
            'full_name' => 'Nguyễn Quản Trị',
            'email' => 'admin@dtdstore.vn',
            'role_id' => 1,
            'role_name' => 'admin'
        ];
    }
}
