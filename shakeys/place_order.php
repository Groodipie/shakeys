<?php
// place_order.php — Processes checkout form
// Inserts into: `Order`, Order_Item, Payment, OrderStatusLog
require_once 'includes/auth_check.php';
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['place_order'])) {
    header('Location: cart.php'); exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) { header('Location: cart.php'); exit; }

$custId  = $_SESSION['cust_id'];
$address = trim($_POST['delivery_address'] ?? '');
$brnchId = (int)($_POST['branch_id']       ?? 0) ?: null;
$promoId = !empty($_POST['promo_id']) ? (int)$_POST['promo_id'] : null;
$payMethod= match($_POST['pay_method'] ?? 'cod') {
    'card'   => 'Credit/Debit Card',
    'online' => 'Online Payment',
    default  => 'Cash on Delivery',
};
$deliveryFee = (float)($_POST['delivery_fee'] ?? 60);
$total       = (float)($_POST['total']        ?? 0);
$crustType   = $_POST['crust_type']   ?? 'Thin Crust';
$instructions= trim($_POST['instructions'] ?? '');

if (!$address || !$brnchId || $total <= 0) {
    header('Location: checkout.php?error=missing_fields'); exit;
}

try {
    $pdo->beginTransaction();

    // 1. Insert Order
    $ordStmt = $pdo->prepare("
        INSERT INTO `Order`
        (Order_Date, Order_Status, Order_TotalAmount, Order_DeliveryAddress,
         Order_DeliveryFee, Order_CustID, Order_BrnchID, Order_PromoID)
        VALUES (NOW(),'Pending',?,?,?,?,?,?)
    ");
    $ordStmt->execute([$total, $address, $deliveryFee, $custId, $brnchId, $promoId]);
    $orderId = $pdo->lastInsertId();

    // 2. Insert Order_Items
    $itemStmt = $pdo->prepare("
        INSERT INTO Order_Item
        (OItem_Qty, OItem_CrustType, OItem_UnitPrice, OItem_AddToppings,
         OItem_Instruction, OItem_OrderID, OItem_ProdID)
        VALUES (?,?,?,?,?,?,?)
    ");
    foreach ($cart as $item) {
        $isPizza = stripos($item['name'],'pizza')!==false
                || in_array(strtolower($item['name']),["manager's choice",'pepperoni','hawaiian','cheese lovers','bacon bbq','spicy veggie']);
        $itemStmt->execute([
            $item['qty'],
            $isPizza ? $crustType : null,
            $item['price'],
            0.00,
            $instructions ?: null,
            $orderId,
            $item['prod_id'],
        ]);
    }

    // 3. Insert Payment
    $payStmt = $pdo->prepare("
        INSERT INTO Payment (Pay_Method, Pay_Status, Pay_Amount, Pay_DateTime, Pay_OrderID)
        VALUES (?,?,?,NOW(),?)
    ");
    $payStatus = ($payMethod === 'Cash on Delivery') ? 'Pending' : 'Paid';
    $payStmt->execute([$payMethod, $payStatus, $total, $orderId]);

    // 4. Insert OrderStatusLog
    $logStmt = $pdo->prepare("
        INSERT INTO OrderStatusLog (OrdLg_Status, OrdLg_ChangedBy, OrdLg_Timestamp, OrdLg_OrderID)
        VALUES ('Pending', ?, NOW(), ?)
    ");
    $logStmt->execute([$_SESSION['cust_firstname'].' '.$_SESSION['cust_lastname'], $orderId]);

    $pdo->commit();

    // Clear cart
    unset($_SESSION['cart']);
    $_SESSION['order_success'] = $orderId;
    header('Location: order_tracking.php?new=' . $orderId);
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    error_log('Order placement failed: ' . $e->getMessage());
    header('Location: checkout.php?error=db_error'); exit;
}
