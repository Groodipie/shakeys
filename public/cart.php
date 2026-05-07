<?php
require_once __DIR__ . '/../app/core/bootstrap.php';
require_login();

$pageTitle = "Cart — Shakey's Delivery";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'], $_POST['qty']) && is_array($_POST['qty'])) {
    Cart::updateQuantities($_POST['qty']);
    header('Location: /cart');
    exit;
}

if (isset($_GET['remove'])) {
    Cart::remove((string)$_GET['remove']);
    header('Location: /cart');
    exit;
}

$cart        = Cart::items();
$subtotal    = Cart::subtotal();
$deliveryFee = $subtotal > 0 ? 60 : 0;
$total       = $subtotal + $deliveryFee;

partial('header', ['pageTitle' => $pageTitle]);
view('cart', compact('cart', 'subtotal', 'deliveryFee', 'total'));
partial('footer');
