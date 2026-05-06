<?php
class Customer {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM Customer WHERE Cust_Email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM Customer WHERE Cust_ID = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function authenticate(string $email, string $password): ?array {
        $cust = $this->findByEmail($email);
        if ($cust && password_verify($password, $cust['Cust_Password'])) {
            return $cust;
        }
        return null;
    }

    public function create(array $data): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO Customer (Cust_FirstName, Cust_LastName, Cust_Email, Cust_Phone, Cust_Address, Cust_Password)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'],
            $data['address'] ?? '',
            password_hash($data['password'], PASSWORD_DEFAULT),
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function updateProfile(int $id, string $first, string $last, string $phone, string $address): void {
        $stmt = $this->pdo->prepare(
            'UPDATE Customer SET Cust_FirstName=?, Cust_LastName=?, Cust_Phone=?, Cust_Address=? WHERE Cust_ID=?'
        );
        $stmt->execute([$first, $last, $phone, $address, $id]);
    }

    public function updatePassword(int $id, string $newPassword): void {
        $stmt = $this->pdo->prepare('UPDATE Customer SET Cust_Password=? WHERE Cust_ID=?');
        $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $id]);
    }
}
