<?php
// add_to_cart.php — Adds a product to the session cart
require_once 'includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prodId   = (int)($_POST['prod_id']    ?? 0);
    $prodName = trim($_POST['prod_name']   ?? '');
    $price    = (float)($_POST['prod_price']?? 0);
    $redirect = $_POST['redirect'] ?? 'menu.php';

    if ($prodId && $prodName && $price > 0) {
        $key = 'prod_' . $prodId;
        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['qty']++;
        } else {
            $_SESSION['cart'][$key] = [
                'prod_id' => $prodId,
                'name'    => $prodName,
                'price'   => $price,
                'qty'     => 1,
            ];
        }
    }
    header('Location: ' . $redirect);
    exit;
}
header('Location: menu.php');
exit;
