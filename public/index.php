<?php
// Front controller — every request enters here.
// Also doubles as the PHP built-in dev server router:
//   php -S localhost:8000 -t public public/index.php

if (php_sapi_name() === 'cli-server') {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($uri !== '/' && is_file(__DIR__ . $uri)) {
        return false; // let the dev server serve static files (assets, etc.)
    }
}

require_once __DIR__ . '/../app/core/bootstrap.php';

$router = require APP_ROOT . '/core/routes.php';

$uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$path   = BASE_URL !== '' && str_starts_with($uri, BASE_URL) ? substr($uri, strlen(BASE_URL)) : $uri;
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router->dispatch($method, $path ?: '/', $pdo);
