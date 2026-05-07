<?php
require_once __DIR__ . '/../app/core/bootstrap.php';

$pageTitle = "Home — Shakey's Delivery";

$products    = new Product($pdo);
$promotions  = new Promotion($pdo);

$recommended  = $products->recommended(3);
$activePromos = $promotions->active(3);
$featured     = $products->byType('Pizza', 5);

partial('header', ['pageTitle' => $pageTitle]);
partial('category_bar');
view('home', compact('recommended', 'activePromos', 'featured'));
partial('footer');
