<?php
require_once __DIR__ . '/../app/core/bootstrap.php';
require_login();

$pageTitle = "Order Tracking — Shakey's Delivery";

$orderModel = new Order($pdo);
$orderList  = $orderModel->listForCustomer((int)$_SESSION['cust_id']);

partial('header', ['pageTitle' => $pageTitle]);
view('order_tracking', compact('orderList', 'orderModel'));
partial('footer');
