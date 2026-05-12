<?php
class HomeController extends Controller {
    public function index(): void {
        $pageTitle = "Home — Shakey's Delivery";

        $products   = new Product($this->pdo);
        $promotions = new Promotion($this->pdo);

        $recommended  = $products->recommended(3);
        $activePromos = $promotions->active(3);
        $featured     = $products->byType('Pizza', 5);

        partial('header', ['pageTitle' => $pageTitle]);
        partial('category_bar');
        view('home', compact('recommended', 'activePromos', 'featured'));
        partial('footer');
    }
}
