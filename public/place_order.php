<?php
require_once __DIR__ . '/../app/core/bootstrap.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['place_order'])) {
    header('Location: ' . url('/cart'));
    exit;
}

$cart = Cart::items();
if (empty($cart)) {
    header('Location: ' . url('/cart'));
    exit;
}

$address = trim($_POST['delivery_address'] ?? '');
$brnchId = (int)($_POST['branch_id'] ?? 0) ?: null;
$total   = (float)($_POST['total'] ?? 0);

if (!$address || !$brnchId || $total <= 0) {
    header('Location: ' . url('/checkout?error=missing_fields'));
    exit;
}

$payMethod = match ($_POST['pay_method'] ?? 'cod') {
    'card'   => 'Credit/Debit Card',
    'online' => 'Online Payment',
    default  => 'Cash on Delivery',
};

$orderData = [
    'cust_id'      => (int)$_SESSION['cust_id'],
    'address'      => $address,
    'branch_id'    => $brnchId,
    'promo_id'     => !empty($_POST['promo_id']) ? (int)$_POST['promo_id'] : null,
    'pay_method'   => $payMethod,
    'delivery_fee' => (float)($_POST['delivery_fee'] ?? 60),
    'total'        => $total,
    'crust_type'   => $_POST['crust_type'] ?? 'Thin Crust',
    'instructions' => trim($_POST['instructions'] ?? ''),
];

try {
    $orderId = (new Order($pdo))->place(
        $orderData,
        $cart,
        $_SESSION['cust_firstname'] . ' ' . $_SESSION['cust_lastname']
    );
    Cart::clear();
    $_SESSION['order_success'] = $orderId;
    header('Location: ' . url('/order_tracking?new=' . $orderId));
    exit;
} catch (PDOException $e) {
    error_log('Order placement failed: ' . $e->getMessage());
    header('Location: ' . url('/checkout?error=db_error'));
    exit;
}
