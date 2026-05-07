<?php
// Router for the PHP built-in dev server.
// Run with: php -S localhost:8000 -t public public/router.php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Let the server handle real static files (assets, *.php files served directly, etc.)
if ($path !== '/' && is_file(__DIR__ . $path)) {
    return false;
}

// Root → home
if ($path === '/' || $path === '') {
    require __DIR__ . '/home.php';
    return;
}

// Pretty product URL: /product/123 → product.php with $_GET['id']=123
if (preg_match('#^/product/(\d+)$#', $path, $m)) {
    $_GET['id'] = $m[1];
    require __DIR__ . '/product.php';
    return;
}

// /foo  →  foo.php
$candidate = __DIR__ . '/' . trim($path, '/') . '.php';
if (is_file($candidate)) {
    require $candidate;
    return;
}

http_response_code(404);
echo 'Not Found';
