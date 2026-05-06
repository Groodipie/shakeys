-- ============================================================
-- SHAKEY'S DELIVERY SYSTEM DATABASE
-- IMDBSYS32 | Bryant James Jabar
-- ============================================================

CREATE DATABASE IF NOT EXISTS shakeys_db;
USE shakeys_db;

-- ─── 1. CUSTOMER ────────────────────────────────────────────────
CREATE TABLE Customer (
    Cust_ID         INT AUTO_INCREMENT PRIMARY KEY,
    Cust_FirstName  VARCHAR(50)  NOT NULL,
    Cust_LastName   VARCHAR(50)  NOT NULL,
    Cust_Phone      CHAR(15)     NOT NULL,
    Cust_Email      VARCHAR(100) NOT NULL UNIQUE,
    Cust_Address    VARCHAR(255) NOT NULL,
    Cust_Password   VARCHAR(255) NOT NULL,
    Cust_CreatedDate DATETIME    DEFAULT CURRENT_TIMESTAMP
);

-- ─── 2. BRANCH ──────────────────────────────────────────────────
CREATE TABLE Branch (
    Brnch_ID            INT AUTO_INCREMENT PRIMARY KEY,
    Brnch_Name          VARCHAR(100) NOT NULL,
    Brnch_Location      VARCHAR(255) NOT NULL,
    Brnch_ContactNumber CHAR(15)     NOT NULL,
    Brnch_ServiceRadius DECIMAL(5,2) NOT NULL,
    Brnch_OpeningTime   TIME         NOT NULL,
    Brnch_ClosingTime   TIME         NOT NULL
);

-- ─── 3. EMPLOYEE ────────────────────────────────────────────────
CREATE TABLE Employee (
    Emp_ID        INT AUTO_INCREMENT PRIMARY KEY,
    Emp_FirstName VARCHAR(100) NOT NULL,
    Emp_LastName  VARCHAR(100) NOT NULL,
    Emp_Phone     VARCHAR(15)  NOT NULL,
    Emp_Role      VARCHAR(100) NOT NULL,
    Emp_BrnchID   INT          NOT NULL,
    FOREIGN KEY (Emp_BrnchID) REFERENCES Branch(Brnch_ID)
);

-- ─── 4. RIDER ───────────────────────────────────────────────────
CREATE TABLE Rider (
    Rider_ID            INT AUTO_INCREMENT PRIMARY KEY,
    Rider_FirstName     VARCHAR(50) NOT NULL,
    Rider_LastName      VARCHAR(50) NOT NULL,
    Rider_ContactNumber CHAR(15)    NOT NULL,
    Rider_Status        ENUM('Available','Unavailable') NOT NULL DEFAULT 'Available'
);

-- ─── 5. PRODUCT ─────────────────────────────────────────────────
CREATE TABLE Product (
    Prod_ID        INT AUTO_INCREMENT PRIMARY KEY,
    Prod_Name      VARCHAR(100)   NOT NULL,
    Prod_Category  VARCHAR(50)    NOT NULL,
    Prod_BasePrice DECIMAL(10,2)  NOT NULL CHECK (Prod_BasePrice >= 0),
    Prod_Type      VARCHAR(50)    NOT NULL,
    Prod_Status    ENUM('Available','Unavailable') NOT NULL DEFAULT 'Available',
    Prod_Desc      VARCHAR(255),
    Prod_Emoji     VARCHAR(10)    DEFAULT '🍕'
);

-- ─── 6. PROMOTION ───────────────────────────────────────────────
CREATE TABLE Promotion (
    Promo_ID          INT AUTO_INCREMENT PRIMARY KEY,
    Promo_Code        VARCHAR(50)  NOT NULL UNIQUE,
    Promo_Name        VARCHAR(100) NOT NULL,
    Promo_Description VARCHAR(255) NOT NULL,
    Promo_Discount    ENUM('Percentage','Fixed') NOT NULL,
    Promo_DiscountValue DECIMAL(10,2) NOT NULL,
    Promo_ValidFrom   DATE         NOT NULL,
    Promo_ValidTo     DATE         NOT NULL,
    Promo_Category    VARCHAR(50)  NOT NULL
);

-- ─── 7. ORDER ───────────────────────────────────────────────────
CREATE TABLE `Order` (
    Order_ID              INT AUTO_INCREMENT PRIMARY KEY,
    Order_Date            DATETIME     DEFAULT CURRENT_TIMESTAMP,
    Order_KitchenStartTime DATETIME    NULL,
    Order_Status          VARCHAR(30)  NOT NULL DEFAULT 'Pending',
    Order_TotalAmount     DECIMAL(10,2) NOT NULL CHECK (Order_TotalAmount >= 0),
    Order_DeliveryAddress VARCHAR(255) NOT NULL,
    Order_DeliveryFee     DECIMAL(10,2) NOT NULL CHECK (Order_DeliveryFee >= 0),
    Order_CustID          INT          NOT NULL,
    Order_BrnchID         INT          NULL,
    Order_PromoID         INT          NULL,
    FOREIGN KEY (Order_CustID)  REFERENCES Customer(Cust_ID),
    FOREIGN KEY (Order_BrnchID) REFERENCES Branch(Brnch_ID),
    FOREIGN KEY (Order_PromoID) REFERENCES Promotion(Promo_ID)
);

-- ─── 8. ORDER_ITEM ──────────────────────────────────────────────
CREATE TABLE Order_Item (
    OItem_ID           INT AUTO_INCREMENT PRIMARY KEY,
    OItem_Qty          INT          NOT NULL CHECK (OItem_Qty BETWEEN 1 AND 999),
    OItem_CrustType    ENUM('Thin Crust','Hand Tossed') NULL,
    OItem_UnitPrice    DECIMAL(10,2) NOT NULL CHECK (OItem_UnitPrice >= 0),
    OItem_AddToppings  DECIMAL(10,2) DEFAULT 0.00,
    OItem_Instruction  VARCHAR(255)  NULL,
    OItem_OrderID      INT          NOT NULL,
    OItem_ProdID       INT          NOT NULL,
    FOREIGN KEY (OItem_OrderID) REFERENCES `Order`(Order_ID),
    FOREIGN KEY (OItem_ProdID)  REFERENCES Product(Prod_ID)
);

-- ─── 9. PAYMENT ─────────────────────────────────────────────────
CREATE TABLE Payment (
    Pay_ID          INT AUTO_INCREMENT PRIMARY KEY,
    Pay_Method      VARCHAR(50)   NOT NULL,
    Pay_Status      VARCHAR(30)   NOT NULL DEFAULT 'Pending',
    Pay_Amount      DECIMAL(10,2) NOT NULL CHECK (Pay_Amount >= 0),
    Pay_Refund      DECIMAL(10,2) DEFAULT 0.00,
    Pay_CODReceived BOOLEAN       DEFAULT FALSE,
    Pay_DateTime    DATETIME      DEFAULT CURRENT_TIMESTAMP,
    Pay_OrderID     INT           NOT NULL,
    FOREIGN KEY (Pay_OrderID) REFERENCES `Order`(Order_ID)
);

-- ─── 10. DELIVERY ───────────────────────────────────────────────
CREATE TABLE Delivery (
    Dlvry_ID            INT AUTO_INCREMENT PRIMARY KEY,
    Dlvry_PickupTime    DATETIME      NULL,
    Dlvry_DeliveryTime  DATETIME      NULL,
    Dlvry_Status        VARCHAR(30)   NOT NULL DEFAULT 'Pending',
    Dlvry_EstimatedTime DATETIME      NULL,
    Dlvry_OrderID       INT           NOT NULL,
    Dlvry_RiderID       INT           NOT NULL,
    FOREIGN KEY (Dlvry_OrderID)  REFERENCES `Order`(Order_ID),
    FOREIGN KEY (Dlvry_RiderID)  REFERENCES Rider(Rider_ID)
);

-- ─── 11. ORDER STATUS LOG ───────────────────────────────────────
CREATE TABLE OrderStatusLog (
    OrdLg_ID        INT AUTO_INCREMENT PRIMARY KEY,
    OrdLg_Status    VARCHAR(30)  NOT NULL,
    OrdLg_ChangedBy VARCHAR(50)  NOT NULL,
    OrdLg_TimeStamp DATETIME     DEFAULT CURRENT_TIMESTAMP,
    OrdLg_OrderID   INT          NOT NULL,
    FOREIGN KEY (OrdLg_OrderID) REFERENCES `Order`(Order_ID)
);

-- ─── 12. BOOKING ────────────────────────────────────────────────
CREATE TABLE Booking (
    Bokng_ID           INT AUTO_INCREMENT PRIMARY KEY,
    Bokng_SchedDateTime DATETIME     NOT NULL,
    Bokng_Status        VARCHAR(50)  NOT NULL DEFAULT 'Pending',
    Bokng_GuestCount    INT          NOT NULL DEFAULT 1,
    Bokng_Notes         VARCHAR(255) NULL,
    Bokng_CreatedAt     DATETIME     DEFAULT CURRENT_TIMESTAMP,
    Bokng_ModifiedAt    DATETIME     NULL ON UPDATE CURRENT_TIMESTAMP,
    Bokng_CancelledAt   DATETIME     NULL,
    Bokng_CustID        INT          NOT NULL,
    Bokng_BrnchID       INT          NOT NULL,
    Bokng_OrderID       INT          NULL,
    FOREIGN KEY (Bokng_CustID)  REFERENCES Customer(Cust_ID),
    FOREIGN KEY (Bokng_BrnchID) REFERENCES Branch(Brnch_ID),
    FOREIGN KEY (Bokng_OrderID) REFERENCES `Order`(Order_ID)
);

-- ============================================================
-- SAMPLE DATA
-- ============================================================

-- Branch
INSERT INTO Branch VALUES
(1,'Shakey\'s SM Cebu','SM City Cebu, North Reclamation Area, Cebu City','032-234-5678',5.00,'10:00:00','21:00:00'),
(2,'Shakey\'s Ayala Cebu','Ayala Center Cebu, Cebu City Business Park','032-345-6789',5.00,'10:00:00','22:00:00'),
(3,'Shakey\'s IT Park','Cybergate Tower 1, Cebu IT Park, Lahug','032-456-7890',4.50,'11:00:00','23:00:00');

-- Employee
INSERT INTO Employee VALUES
(1,'Maria','Santos','09171111111','Branch Manager',1),
(2,'Jose','Reyes','09182222222','Kitchen Staff',1),
(3,'Ana','Cruz','09193333333','Cashier',2);

-- Rider
INSERT INTO Rider VALUES
(1,'Juan','Dela Cruz','+639171234567','Available'),
(2,'Mark','Santos','+639281234567','Available'),
(3,'Carlo','Ramos','+639391234567','Unavailable');

-- Product
INSERT INTO Product (Prod_Name,Prod_Category,Prod_BasePrice,Prod_Type,Prod_Status,Prod_Desc,Prod_Emoji) VALUES
('Manager\'s Choice','All-Time Favorites',399.00,'Pizza','Available','Ham, beef, Italian sausage, mushroom, green pepper, black olives','🍕'),
('Pepperoni','All-Time Favorites',349.00,'Pizza','Available','Classic pepperoni with rich tomato sauce and mozzarella','🍕'),
('Hawaiian','All-Time Favorites',329.00,'Pizza','Available','Ham and pineapple on golden crust with mozzarella','🍕'),
('Cheese Lovers','Specialty',379.00,'Pizza','Available','A blend of three premium cheeses on Shakey\'s signature crust','🍕'),
('Spicy Veggie','Specialty',299.00,'Pizza','Available','Fresh garden vegetables with a spicy kick','🍕'),
('Bacon BBQ','Specialty',389.00,'Pizza','Available','Crispy bacon in rich BBQ sauce with caramelized onions','🍕'),
('Chicken \'N Mojos','Chicken',189.00,'Chicken','Available','Crispy golden chicken with signature Mojos potato rounds','🍗'),
('Parmigiana Chicken Royale','Chicken',269.00,'Chicken','Available','100% Chicken Thigh Fillet, parmigiana sauce, buttered rice','🍗'),
('Truffle Mushroom Royale','Chicken',279.00,'Chicken','Available','Truffle mushroom cream sauce, buttered rice, corn & carrots','🍗'),
('Garlic Butter Shrimp \'N\' Scallop','Seafood',249.00,'Side','Available','Breaded scallops & shrimps in garlic butter, buttered rice','🦐'),
('Spaghetti','Pasta',159.00,'Pasta','Available','Shakey\'s classic spaghetti with rich meat sauce','🍝'),
('Mojos Potatoes','Sides',99.00,'Side','Available','Crispy seasoned potato rounds — Shakey\'s signature side','🍟'),
('Mozzarella Cheese Sticks','Sides',129.00,'Side','Available','Golden fried mozzarella sticks with marinara dipping sauce','🧀'),
('1.5L Coke','Beverages',79.00,'Beverage','Available','Refreshing 1.5L Coca-Cola','🥤'),
('Cheesy Garlic Bread','Sides',89.00,'Side','Available','Freshly baked garlic bread loaded with melted cheese','🥖');

-- Promotion
INSERT INTO Promotion VALUES
(1,'HBO999','HBO Bundle','1 Large Thin Crust Pizza + 4pcs Chicken N Mojos + Garlic Bread + 1.5L Coke','Fixed',689.00,'2026-04-01','2026-06-30','Pizza'),
(2,'GRAD999','Graduation Meal Deal','1 Large Pizza + 4pcs Chicken N Mojos + 4pcs Mozz Sticks + 1.5L Coke + Coupons','Fixed',400.00,'2026-03-01','2026-06-15','Group Meals'),
(3,'SUPERB10','Supercard 10% Off','10% discount on all orders for Supercard Classic holders','Percentage',10.00,'2026-01-01','2026-12-31','All'),
(4,'B1T1PIZZA','Classic Pizza Americana B1T1','Buy 1 Take 1 18-inch Classic Pizza Americana + 1.5L Coke','Fixed',500.00,'2026-04-01','2026-05-31','Pizza'),
(5,'SUPERPLATE','Super Plates Launch','New Super Plates — Bigger portions, bolder flavors at intro price','Percentage',15.00,'2026-05-01','2026-07-31','Chicken');

-- Demo Customer (password: password123)
INSERT INTO Customer (Cust_FirstName,Cust_LastName,Cust_Phone,Cust_Email,Cust_Address,Cust_Password) VALUES
('Bryant James','Jabar','+639605575303','jabarbj3@gmail.com','123 Colon St., Cebu City',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
