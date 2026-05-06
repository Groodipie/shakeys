<?php
define('APP_ROOT', dirname(__DIR__));

if (session_status() === PHP_SESSION_NONE) session_start();

require_once APP_ROOT . '/core/db.php';
require_once APP_ROOT . '/core/auth.php';

spl_autoload_register(function ($class) {
    $file = APP_ROOT . '/models/' . $class . '.php';
    if (is_file($file)) require_once $file;
});

function view(string $name, array $data = []): void {
    extract($data, EXTR_SKIP);
    require APP_ROOT . '/views/' . $name . '.php';
}

function partial(string $name, array $data = []): void {
    extract($data, EXTR_SKIP);
    require APP_ROOT . '/views/partials/' . $name . '.php';
}

function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
