<?php
require_once __DIR__ . '/../app/core/bootstrap.php';
require_login();

$pageTitle = "Home — Shakey's Delivery";

$products    = new Product($pdo);
$promotions  = new Promotion($pdo);

$recommended  = $products->recommended(4);
$activePromos = $promotions->active(3);
$pizzas       = $products->byType('Pizza', 6);

partial('header', ['pageTitle' => $pageTitle]);
view('home', compact('recommended', 'activePromos', 'pizzas'));
partial('footer');
