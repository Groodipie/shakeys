<?php
class PromosController extends Controller {
    public function index(): void {
        require_login();

        $pageTitle = "Promos — Shakey's Delivery";
        $tab = $_GET['tab'] ?? 'promo';

        $promotions    = new Promotion($this->pdo);
        $activePromos  = $promotions->active();
        $expiredPromos = $promotions->expired(6);

        partial('header', ['pageTitle' => $pageTitle]);
        view('promos', compact('tab', 'activePromos', 'expiredPromos'));
        partial('footer');
    }
}
