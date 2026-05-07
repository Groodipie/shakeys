<?php
class Product {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function find(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM Product WHERE Prod_ID=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function recommended(int $limit = 4): array {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM Product WHERE Prod_Status='Available' ORDER BY Prod_ID LIMIT $limit"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function byType(string $type, int $limit = 6): array {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM Product WHERE Prod_Type=? AND Prod_Status='Available' LIMIT $limit"
        );
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    public function categories(): array {
        return $this->pdo->query(
            "SELECT DISTINCT Prod_Category FROM Product WHERE Prod_Status='Available' ORDER BY Prod_Category"
        )->fetchAll();
    }

    public function search(?string $category = null, ?string $search = null): array {
        $sql = "SELECT * FROM Product WHERE Prod_Status='Available'";
        $params = [];
        if ($category) { $sql .= ' AND Prod_Category = ?'; $params[] = $category; }
        if ($search)   { $sql .= ' AND Prod_Name LIKE ?';  $params[] = "%$search%"; }
        $sql .= ' ORDER BY Prod_Category, Prod_Name';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
