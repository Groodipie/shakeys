<?php
class PizzaOptions {
    const CRUSTS = ['Thin Crust', 'Hand-Tossed'];

    const SIZES = [
        'Regular' => ['multiplier' => 1.0, 'desc' => 'Good For 1-2 Persons'],
        'Large'   => ['multiplier' => 1.5, 'desc' => 'Good For 3-4 Persons'],
        'Party'   => ['multiplier' => 2.0, 'desc' => 'Good For 4-6 Persons'],
    ];

    const TOPPINGS = [
        'Beef'              => 40.00,
        'Cheese'            => 50.00,
        'Green Bell Pepper' => 30.00,
        'Ham'               => 40.00,
        'Onion'             => 30.00,
        'Pineapple'         => 30.00,
        'Sausage'           => 40.00,
    ];

    public static function sizeMultiplier(string $size): float {
        return self::SIZES[$size]['multiplier'] ?? 1.0;
    }

    public static function toppingPrice(string $topping, string $size): float {
        $base = self::TOPPINGS[$topping] ?? 0.0;
        return round($base * self::sizeMultiplier($size), 2);
    }

    public static function unitPrice(float $basePrice, string $size): float {
        return round($basePrice * self::sizeMultiplier($size), 2);
    }

    public static function toppingsTotal(array $toppings, string $size): float {
        $sum = 0.0;
        foreach ($toppings as $t) $sum += self::toppingPrice($t, $size);
        return round($sum, 2);
    }

    public static function isValidCrust(string $c): bool { return in_array($c, self::CRUSTS, true); }
    public static function isValidSize(string $s): bool  { return isset(self::SIZES[$s]); }
    public static function isValidTopping(string $t): bool { return isset(self::TOPPINGS[$t]); }
}
