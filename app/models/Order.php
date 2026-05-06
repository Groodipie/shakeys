<?php
class Order {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function branches(): array {
        return $this->pdo->query('SELECT * FROM Branch ORDER BY Brnch_Name')->fetchAll();
    }

    public function countForCustomer(int $custId): int {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM `Order` WHERE Order_CustID=?');
        $stmt->execute([$custId]);
        return (int)$stmt->fetchColumn();
    }

    public function listForCustomer(int $custId): array {
        $stmt = $this->pdo->prepare(
            "SELECT o.*,
                    b.Brnch_Name,
                    p.Pay_Method, p.Pay_Status,
                    d.Dlvry_Status, d.Dlvry_EstimatedTime,
                    CONCAT(r.Rider_FirstName,' ',r.Rider_LastName) AS Rider_Name,
                    r.Rider_ContactNumber
             FROM `Order` o
             LEFT JOIN Branch   b ON b.Brnch_ID    = o.Order_BrnchID
             LEFT JOIN Payment  p ON p.Pay_OrderID = o.Order_ID
             LEFT JOIN Delivery d ON d.Dlvry_OrderID = o.Order_ID
             LEFT JOIN Rider    r ON r.Rider_ID    = d.Dlvry_RiderID
             WHERE o.Order_CustID = ?
             ORDER BY o.Order_Date DESC"
        );
        $stmt->execute([$custId]);
        return $stmt->fetchAll();
    }

    public function items(int $orderId): array {
        $stmt = $this->pdo->prepare(
            'SELECT oi.*, p.Prod_Name, p.Prod_Type
             FROM Order_Item oi JOIN Product p ON p.Prod_ID = oi.OItem_ProdID
             WHERE oi.OItem_OrderID = ?'
        );
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function place(array $orderData, array $cart, string $changedBy): int {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO `Order`
                 (Order_Date, Order_Status, Order_TotalAmount, Order_DeliveryAddress,
                  Order_DeliveryFee, Order_CustID, Order_BrnchID, Order_PromoID)
                 VALUES (NOW(),'Pending',?,?,?,?,?,?)"
            );
            $stmt->execute([
                $orderData['total'],
                $orderData['address'],
                $orderData['delivery_fee'],
                $orderData['cust_id'],
                $orderData['branch_id'],
                $orderData['promo_id'],
            ]);
            $orderId = (int)$this->pdo->lastInsertId();

            $itemStmt = $this->pdo->prepare(
                'INSERT INTO Order_Item
                 (OItem_Qty, OItem_CrustType, OItem_UnitPrice, OItem_AddToppings,
                  OItem_Instruction, OItem_OrderID, OItem_ProdID)
                 VALUES (?,?,?,?,?,?,?)'
            );
            foreach ($cart as $item) {
                $isPizza = self::isPizza($item['name']);
                $itemStmt->execute([
                    $item['qty'],
                    $isPizza ? $orderData['crust_type'] : null,
                    $item['price'],
                    0.00,
                    $orderData['instructions'] ?: null,
                    $orderId,
                    $item['prod_id'],
                ]);
            }

            $payStatus = ($orderData['pay_method'] === 'Cash on Delivery') ? 'Pending' : 'Paid';
            $payStmt = $this->pdo->prepare(
                'INSERT INTO Payment (Pay_Method, Pay_Status, Pay_Amount, Pay_DateTime, Pay_OrderID)
                 VALUES (?,?,?,NOW(),?)'
            );
            $payStmt->execute([$orderData['pay_method'], $payStatus, $orderData['total'], $orderId]);

            $logStmt = $this->pdo->prepare(
                "INSERT INTO OrderStatusLog (OrdLg_Status, OrdLg_ChangedBy, OrdLg_Timestamp, OrdLg_OrderID)
                 VALUES ('Pending', ?, NOW(), ?)"
            );
            $logStmt->execute([$changedBy, $orderId]);

            $this->pdo->commit();
            return $orderId;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public static function isPizza(string $name): bool {
        if (stripos($name, 'pizza') !== false) return true;
        return in_array(strtolower($name), [
            "manager's choice", 'pepperoni', 'hawaiian', 'cheese lovers', 'bacon bbq', 'spicy veggie',
        ], true);
    }

    public static function cartHasPizza(array $cart): bool {
        foreach ($cart as $item) {
            if (self::isPizza($item['name'])) return true;
        }
        return false;
    }
}
