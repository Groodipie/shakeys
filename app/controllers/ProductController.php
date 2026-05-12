<?php
class ProductController extends Controller {
    public function show(int $id): void {
        require_login();

        $products = new Product($this->pdo);
        $product  = $id ? $products->find($id) : null;

        if (!$product) {
            http_response_code(404);
            $pageTitle = 'Product not found';
            partial('header', ['pageTitle' => $pageTitle]);
            echo '<div class="container py-5 text-center"><h4>Product not found</h4><a href="' . e(url('/menu')) . '" class="btn mt-3" style="background:var(--sk-red);color:#fff;">Back to Menu</a></div>';
            partial('footer');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->addToCart($product, $id);
            return;
        }

        $editKey = $_GET['edit'] ?? '';
        $editing = null;
        if ($editKey !== '') {
            $items = Cart::items();
            if (isset($items[$editKey]) && (int)$items[$editKey]['prod_id'] === (int)$product['Prod_ID']) {
                $editing = $items[$editKey];
            }
        }

        $pageTitle = e($product['Prod_Name']) . " — Shakey's Delivery";
        partial('header', ['pageTitle' => $pageTitle]);
        view('product', compact('product', 'editing', 'editKey'));
        partial('footer', ['hideFooter' => true]);
    }

    private function addToCart(array $product, int $id): void {
        $crust    = $_POST['crust']  ?? '';
        $size     = $_POST['size']   ?? '';
        $qty      = max(1, (int)($_POST['qty'] ?? 1));
        $toppings = array_values(array_filter((array)($_POST['toppings'] ?? []), 'is_string'));
        $editKey  = $_POST['edit_key'] ?? '';

        if (!PizzaOptions::isValidCrust($crust) || !PizzaOptions::isValidSize($size)) {
            $this->redirect('/product/' . $id . '?error=invalid' . ($editKey ? '&edit=' . urlencode($editKey) : ''));
        }
        foreach ($toppings as $t) {
            if (!PizzaOptions::isValidTopping($t)) {
                $this->redirect('/product/' . $id . '?error=invalid' . ($editKey ? '&edit=' . urlencode($editKey) : ''));
            }
        }

        $base = (float)$product['Prod_BasePrice'];
        $unit = PizzaOptions::unitPrice($base, $size);
        $tops = PizzaOptions::toppingsTotal($toppings, $size);

        if ($editKey !== '') Cart::remove($editKey);

        Cart::addCustom(
            (int)$product['Prod_ID'],
            $product['Prod_Name'],
            $unit,
            $qty,
            $crust,
            $size,
            $toppings,
            $tops
        );
        $this->redirect('/cart');
    }
}
