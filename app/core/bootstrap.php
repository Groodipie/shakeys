<?php
define('APP_ROOT', dirname(__DIR__));

if (session_status() === PHP_SESSION_NONE) session_start();

// Detect URL prefix so the same code works under the PHP dev server
// (docroot = public/) and Apache served from a subfolder (e.g. /shakeys/public).
if (php_sapi_name() === 'cli-server') {
    define('BASE_URL', '');
} else {
    $sn = $_SERVER['SCRIPT_NAME'] ?? '';
    $dir = str_replace('\\', '/', dirname($sn));
    define('BASE_URL', $dir === '/' || $dir === '.' ? '' : rtrim($dir, '/'));
}

function url(string $path = ''): string {
    if ($path === '') return BASE_URL === '' ? '/' : BASE_URL;
    return BASE_URL . ($path[0] === '/' ? $path : '/' . $path);
}

require_once APP_ROOT . '/core/db.php';
require_once APP_ROOT . '/core/auth.php';

spl_autoload_register(function ($class) {
    foreach (['/models/', '/controllers/', '/core/'] as $dir) {
        $file = APP_ROOT . $dir . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
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
