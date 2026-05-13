<?php
class Employee {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findByCredentials(int $id, string $phone): ?array {
        $sql = 'SELECT e.*, b.Brnch_Name
                FROM Employee e
                LEFT JOIN Branch b ON b.Brnch_ID = e.Emp_BrnchID
                WHERE e.Emp_ID = ? AND e.Emp_Phone = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id, $phone]);
        return $stmt->fetch() ?: null;
    }

    public function all(): array {
        $sql = 'SELECT e.*, b.Brnch_Name
                FROM Employee e
                LEFT JOIN Branch b ON b.Brnch_ID = e.Emp_BrnchID
                ORDER BY e.Emp_ID DESC';
        return $this->pdo->query($sql)->fetchAll() ?: [];
    }

    public function create(array $data): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO Employee (Emp_FirstName, Emp_LastName, Emp_Phone, Emp_Role, Emp_BrnchID)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['phone'],
            $data['role'],
            (int)$data['branch_id'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void {
        $stmt = $this->pdo->prepare(
            'UPDATE Employee
                SET Emp_FirstName = ?, Emp_LastName = ?, Emp_Phone = ?, Emp_Role = ?, Emp_BrnchID = ?
              WHERE Emp_ID = ?'
        );
        $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['phone'],
            $data['role'],
            (int)$data['branch_id'],
            $id,
        ]);
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM Employee WHERE Emp_ID = ?');
        $stmt->execute([$id]);
    }

    public function branches(): array {
        return $this->pdo->query('SELECT Brnch_ID, Brnch_Name FROM Branch ORDER BY Brnch_Name')->fetchAll() ?: [];
    }
}
