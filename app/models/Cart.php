<?php
class Cart {
    public static function items(): array {
        return $_SESSION['cart'] ?? [];
    }

    public static function add(int $prodId, string $name, float $price): void {
        $key = 'prod_' . $prodId;
        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['qty']++;
        } else {
            $_SESSION['cart'][$key] = [
                'prod_id' => $prodId,
                'name'    => $name,
                'price'   => $price,
                'qty'     => 1,
            ];
        }
    }

    public static function addCustom(
        int $prodId,
        string $name,
        float $unitPrice,
        int $qty,
        string $crust,
        string $size,
        array $toppings,
        float $toppingsTotal
    ): void {
        sort($toppings);
        $signature = $crust . '|' . $size . '|' . implode(',', $toppings);
        $key = 'prod_' . $prodId . '_' . substr(md5($signature), 0, 8);
        $linePrice = round($unitPrice + $toppingsTotal, 2);

        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$key] = [
                'prod_id'        => $prodId,
                'name'           => $name,
                'price'          => $linePrice,
                'qty'            => $qty,
                'crust'          => $crust,
                'size'           => $size,
                'toppings'       => $toppings,
                'toppings_total' => $toppingsTotal,
                'base_price'     => $unitPrice,
            ];
        }
    }

    public static function updateQuantities(array $qtyMap): void {
        foreach ($qtyMap as $key => $qty) {
            $qty = max(1, (int)$qty);
            if (isset($_SESSION['cart'][$key])) {
                $_SESSION['cart'][$key]['qty'] = $qty;
            }
        }
    }

    public static function remove(string $key): void {
        unset($_SESSION['cart'][$key]);
    }

    public static function clear(): void {
        unset($_SESSION['cart']);
    }

    public static function subtotal(): float {
        $total = 0.0;
        foreach (self::items() as $item) $total += $item['price'] * $item['qty'];
        return $total;
    }

    public static function itemCount(): int {
        return array_sum(array_column(self::items(), 'qty'));
    }

    public static function isEmpty(): bool {
        return empty($_SESSION['cart']);
    }
}
