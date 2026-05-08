<?php
require_once __DIR__ . '/../app/core/bootstrap.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prodId   = (int)($_POST['prod_id'] ?? 0);
    $prodName = trim($_POST['prod_name'] ?? '');
    $price    = (float)($_POST['prod_price'] ?? 0);
    $redirect = $_POST['redirect'] ?? '/menu';

    if ($prodId && $prodName && $price > 0) {
        Cart::add($prodId, $prodName, $price);
    }
    header('Location: ' . url($redirect));
    exit;
}

header('Location: ' . url('/menu'));
exit;
