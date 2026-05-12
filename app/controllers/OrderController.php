<?php
class OrderController extends Controller {
    public function tracking(): void {
        require_login();

        $pageTitle = "Order Tracking — Shakey's Delivery";

        $orderModel = new Order($this->pdo);
        $orderList  = $orderModel->listForCustomer((int)$_SESSION['cust_id']);

        partial('header', ['pageTitle' => $pageTitle]);
        view('order_tracking', compact('orderList', 'orderModel'));
        partial('footer');
    }
}
