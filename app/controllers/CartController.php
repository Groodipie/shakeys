<?php
class CartController extends Controller {
    public function index(): void {
        require_login();

        $pageTitle = "Cart — Shakey's Delivery";

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'], $_POST['qty']) && is_array($_POST['qty'])) {
            Cart::updateQuantities($_POST['qty']);
            $this->redirect('/cart');
        }

        if (isset($_GET['remove'])) {
            Cart::remove((string)$_GET['remove']);
            $this->redirect('/cart');
        }

        $cart        = Cart::items();
        $subtotal    = Cart::subtotal();
        $deliveryFee = $subtotal > 0 ? 60 : 0;
        $total       = $subtotal + $deliveryFee;

        partial('header', ['pageTitle' => $pageTitle]);
        view('cart', compact('cart', 'subtotal', 'deliveryFee', 'total'));
        partial('footer');
    }

    public function add(): void {
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/menu');
        }

        $prodId   = (int)($_POST['prod_id'] ?? 0);
        $prodName = trim($_POST['prod_name'] ?? '');
        $price    = (float)($_POST['prod_price'] ?? 0);
        $redirect = $_POST['redirect'] ?? '/menu';

        if ($prodId && $prodName && $price > 0) {
            Cart::add($prodId, $prodName, $price);
        }
        $this->redirect($redirect);
    }
}
