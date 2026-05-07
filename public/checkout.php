<?php
require_once __DIR__ . '/../app/core/bootstrap.php';
require_login();

$pageTitle = "Checkout — Shakey's Delivery";

$cart = Cart::items();
if (empty($cart)) {
    header('Location: /cart');
    exit;
}

$cust    = (new Customer($pdo))->findById((int)$_SESSION['cust_id']);
$branches = (new Order($pdo))->branches();

$subtotal = Cart::subtotal();

$promoData  = null;
$promoError = '';
if (!empty($_POST['promo_code'])) {
    $promoData = (new Promotion($pdo))->findByCode($_POST['promo_code']);
    if (!$promoData) $promoError = 'Invalid or expired promo code.';
}

$discount    = $promoData ? Promotion::calculateDiscount($promoData, $subtotal) : 0;
$deliveryFee = 60;
$total       = max(0, $subtotal - $discount) + $deliveryFee;
$hasPizza    = Order::cartHasPizza($cart);

partial('header', ['pageTitle' => $pageTitle]);
view('checkout', compact('cart', 'cust', 'branches', 'subtotal', 'promoData', 'promoError', 'discount', 'deliveryFee', 'total', 'hasPizza'));
partial('footer');
