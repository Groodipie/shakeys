<?php
class CheckoutController extends Controller {
    public function index(): void {
        require_login();

        $pageTitle = "Checkout — Shakey's Delivery";

        $cart = Cart::items();
        if (empty($cart)) {
            $this->redirect('/cart');
        }

        $cust     = (new Customer($this->pdo))->findById((int)$_SESSION['cust_id']);
        $branches = (new Order($this->pdo))->branches();

        $subtotal = Cart::subtotal();

        $promoData  = null;
        $promoError = '';
        if (!empty($_POST['promo_code'])) {
            $promoData = (new Promotion($this->pdo))->findByCode($_POST['promo_code']);
            if (!$promoData) $promoError = 'Invalid or expired promo code.';
        }

        $discount    = $promoData ? Promotion::calculateDiscount($promoData, $subtotal) : 0;
        $deliveryFee = 60;
        $total       = max(0, $subtotal - $discount) + $deliveryFee;
        $hasPizza    = Order::cartHasPizza($cart);

        partial('header', ['pageTitle' => $pageTitle]);
        view('checkout', compact('cart', 'cust', 'branches', 'subtotal', 'promoData', 'promoError', 'discount', 'deliveryFee', 'total', 'hasPizza'));
        partial('footer');
    }

    public function place(): void {
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['place_order'])) {
            $this->redirect('/cart');
        }

        $cart = Cart::items();
        if (empty($cart)) {
            $this->redirect('/cart');
        }

        $address = trim($_POST['delivery_address'] ?? '');
        $brnchId = (int)($_POST['branch_id'] ?? 0) ?: null;
        $total   = (float)($_POST['total'] ?? 0);

        if (!$address || !$brnchId || $total <= 0) {
            $this->redirect('/checkout?error=missing_fields');
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
            $orderId = (new Order($this->pdo))->place(
                $orderData,
                $cart,
                $_SESSION['cust_firstname'] . ' ' . $_SESSION['cust_lastname']
            );
            Cart::clear();
            $_SESSION['order_success'] = $orderId;
            $this->redirect('/order_tracking?new=' . $orderId);
        } catch (PDOException $e) {
            error_log('Order placement failed: ' . $e->getMessage());
            $this->redirect('/checkout?error=db_error');
        }
    }
}
