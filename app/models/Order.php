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

    public function listForBranch(int $branchId): array {
        $stmt = $this->pdo->prepare(
            "SELECT o.*,
                    CONCAT(c.Cust_FirstName,' ',c.Cust_LastName) AS Cust_Name,
                    c.Cust_Phone,
                    p.Pay_Method, p.Pay_Status,
                    d.Dlvry_RiderID,
                    CONCAT(r.Rider_FirstName,' ',r.Rider_LastName) AS Rider_Name
             FROM `Order` o
             LEFT JOIN Customer c ON c.Cust_ID      = o.Order_CustID
             LEFT JOIN Payment  p ON p.Pay_OrderID  = o.Order_ID
             LEFT JOIN Delivery d ON d.Dlvry_OrderID = o.Order_ID
             LEFT JOIN Rider    r ON r.Rider_ID     = d.Dlvry_RiderID
             WHERE o.Order_BrnchID = ?
             ORDER BY o.Order_Date DESC"
        );
        $stmt->execute([$branchId]);
        return $stmt->fetchAll();
    }

    public function listForRider(int $riderId): array {
        $stmt = $this->pdo->prepare(
            "SELECT o.*,
                    CONCAT(c.Cust_FirstName,' ',c.Cust_LastName) AS Cust_Name,
                    c.Cust_Phone,
                    b.Brnch_Name,
                    p.Pay_Method, p.Pay_Status,
                    d.Dlvry_Status, d.Dlvry_EstimatedTime
             FROM `Order` o
             JOIN Delivery d ON d.Dlvry_OrderID = o.Order_ID
             LEFT JOIN Customer c ON c.Cust_ID     = o.Order_CustID
             LEFT JOIN Branch   b ON b.Brnch_ID    = o.Order_BrnchID
             LEFT JOIN Payment  p ON p.Pay_OrderID = o.Order_ID
             WHERE d.Dlvry_RiderID = ?
             ORDER BY o.Order_Date DESC"
        );
        $stmt->execute([$riderId]);
        return $stmt->fetchAll();
    }

    public function assignRider(int $orderId, int $riderId, string $changedBy): void {
        $this->pdo->beginTransaction();
        try {
            $check = $this->pdo->prepare('SELECT Dlvry_ID FROM Delivery WHERE Dlvry_OrderID = ?');
            $check->execute([$orderId]);
            $existing = $check->fetchColumn();

            if ($existing) {
                $upd = $this->pdo->prepare('UPDATE Delivery SET Dlvry_RiderID = ? WHERE Dlvry_OrderID = ?');
                $upd->execute([$riderId, $orderId]);
            } else {
                $ins = $this->pdo->prepare(
                    "INSERT INTO Delivery (Dlvry_Status, Dlvry_OrderID, Dlvry_RiderID)
                     VALUES ('Assigned', ?, ?)"
                );
                $ins->execute([$orderId, $riderId]);
            }

            $statusUpd = $this->pdo->prepare("UPDATE `Order` SET Order_Status = 'Ready' WHERE Order_ID = ?");
            $statusUpd->execute([$orderId]);

            $log = $this->pdo->prepare(
                "INSERT INTO OrderStatusLog (OrdLg_Status, OrdLg_ChangedBy, OrdLg_Timestamp, OrdLg_OrderID)
                 VALUES ('Ready', ?, NOW(), ?)"
            );
            $log->execute([$changedBy, $orderId]);

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function markInTransit(int $orderId, int $riderId, string $changedBy): bool {
        $check = $this->pdo->prepare(
            'SELECT 1 FROM Delivery WHERE Dlvry_OrderID = ? AND Dlvry_RiderID = ?'
        );
        $check->execute([$orderId, $riderId]);
        if (!$check->fetchColumn()) {
            return false;
        }

        $this->pdo->beginTransaction();
        try {
            $upd = $this->pdo->prepare(
                "UPDATE Delivery
                    SET Dlvry_Status = 'In Transit',
                        Dlvry_PickTime = IFNULL(Dlvry_PickTime, NOW())
                  WHERE Dlvry_OrderID = ? AND Dlvry_RiderID = ?"
            );
            $upd->execute([$orderId, $riderId]);

            $statusUpd = $this->pdo->prepare("UPDATE `Order` SET Order_Status = 'In Transit' WHERE Order_ID = ?");
            $statusUpd->execute([$orderId]);

            $log = $this->pdo->prepare(
                "INSERT INTO OrderStatusLog (OrdLg_Status, OrdLg_ChangedBy, OrdLg_Timestamp, OrdLg_OrderID)
                 VALUES ('In Transit', ?, NOW(), ?)"
            );
            $log->execute([$changedBy, $orderId]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function markDelivered(int $orderId, int $riderId, string $changedBy): bool {
        $check = $this->pdo->prepare(
            'SELECT 1 FROM Delivery WHERE Dlvry_OrderID = ? AND Dlvry_RiderID = ?'
        );
        $check->execute([$orderId, $riderId]);
        if (!$check->fetchColumn()) {
            return false;
        }

        $this->pdo->beginTransaction();
        try {
            $upd = $this->pdo->prepare(
                "UPDATE Delivery
                    SET Dlvry_Status = 'Delivered',
                        Dlvry_DeliveryTime = NOW()
                  WHERE Dlvry_OrderID = ? AND Dlvry_RiderID = ?"
            );
            $upd->execute([$orderId, $riderId]);

            $statusUpd = $this->pdo->prepare("UPDATE `Order` SET Order_Status = 'Delivered' WHERE Order_ID = ?");
            $statusUpd->execute([$orderId]);

            $payUpd = $this->pdo->prepare(
                "UPDATE Payment SET Pay_Status = 'Paid', Pay_DateTime = NOW()
                  WHERE Pay_OrderID = ? AND Pay_Status <> 'Paid'"
            );
            $payUpd->execute([$orderId]);

            $log = $this->pdo->prepare(
                "INSERT INTO OrderStatusLog (OrdLg_Status, OrdLg_ChangedBy, OrdLg_Timestamp, OrdLg_OrderID)
                 VALUES ('Delivered', ?, NOW(), ?)"
            );
            $log->execute([$changedBy, $orderId]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
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
                 VALUES (NOW(),'Preparing',?,?,?,?,?,?)"
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
                $itemCrust = $item['crust'] ?? ($isPizza ? $orderData['crust_type'] : null);
                if ($itemCrust === 'Hand-Tossed') $itemCrust = 'Hand Tossed';
                $instructionParts = [];
                if (!empty($item['size']))     $instructionParts[] = 'Size: ' . $item['size'];
                if (!empty($item['toppings'])) $instructionParts[] = 'Toppings: ' . implode(', ', $item['toppings']);
                if (!empty($orderData['instructions'])) $instructionParts[] = $orderData['instructions'];
                $instruction = $instructionParts ? implode(' | ', $instructionParts) : null;

                $itemStmt->execute([
                    $item['qty'],
                    $itemCrust,
                    $item['price'],
                    (float)($item['toppings_total'] ?? 0),
                    $instruction,
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
                 VALUES ('Preparing', ?, NOW(), ?)"
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
