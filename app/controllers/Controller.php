<?php
abstract class Controller {
    protected PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    protected function redirect(string $path): void {
        header('Location: ' . url($path));
        exit;
    }
}
