<?php
require_once __DIR__ . '/../app/core/bootstrap.php';
require_login();

$pageTitle = "Menu — Shakey's Delivery";

$category = $_GET['category'] ?? '';
$search   = trim($_GET['search'] ?? '');

$products   = new Product($pdo);
$categories = $products->categories();
$products   = $products->search($category ?: null, $search ?: null);

partial('header', ['pageTitle' => $pageTitle]);
view('menu', compact('categories', 'products', 'category', 'search'));
partial('footer');
