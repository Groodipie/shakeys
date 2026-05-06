<?php
class Promotion {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function active(?int $limit = null): array {
        $today = date('Y-m-d');
        $sql = 'SELECT * FROM Promotion WHERE Promo_ValidFrom <= ? AND Promo_ValidTo >= ? ORDER BY Promo_ID DESC';
        if ($limit) $sql .= ' LIMIT ' . (int)$limit;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$today, $today]);
        return $stmt->fetchAll();
    }

    public function expired(int $limit = 6): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM Promotion WHERE Promo_ValidTo < ? ORDER BY Promo_ValidTo DESC LIMIT ' . (int)$limit
        );
        $stmt->execute([date('Y-m-d')]);
        return $stmt->fetchAll();
    }

    public function findByCode(string $code): ?array {
        $today = date('Y-m-d');
        $stmt = $this->pdo->prepare(
            'SELECT * FROM Promotion WHERE Promo_Code=? AND Promo_ValidFrom<=? AND Promo_ValidTo>=?'
        );
        $stmt->execute([$code, $today, $today]);
        return $stmt->fetch() ?: null;
    }

    public static function calculateDiscount(array $promo, float $subtotal): float {
        if ($promo['Promo_Discount'] === 'Fixed') {
            return (float)$promo['Promo_DiscountValue'];
        }
        return round($subtotal * $promo['Promo_DiscountValue'] / 100, 2);
    }
}
