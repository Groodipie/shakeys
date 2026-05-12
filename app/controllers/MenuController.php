<?php
class MenuController extends Controller {
    public function index(): void {
        require_login();

        $pageTitle = "Menu — Shakey's Delivery";

        $category = $_GET['category'] ?? '';
        $search   = trim($_GET['search'] ?? '');

        $products   = new Product($this->pdo);
        $categories = $products->categories();
        $products   = $products->search($category ?: null, $search ?: null);

        partial('header', ['pageTitle' => $pageTitle]);
        partial('category_bar');
        view('menu', compact('categories', 'products', 'category', 'search'));
        partial('footer');
    }
}
