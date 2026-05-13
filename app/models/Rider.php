<?php
class Rider {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function available(): array {
        return $this->pdo->query(
            "SELECT * FROM Rider WHERE Rider_Status = 'Available' ORDER BY Rider_FirstName, Rider_LastName"
        )->fetchAll();
    }
}
