-- ============================================================
-- SHAKEY'S DELIVERY SYSTEM — DATABASE SCHEMA
-- Subject: IMDBSYS32 | Bryant James Jabar
-- ============================================================

CREATE DATABASE IF NOT EXISTS shakeyspizza CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shakeyspizza;

-- 1. Customer
CREATE TABLE Customer (
    Cust_ID         INT AUTO_INCREMENT PRIMARY KEY,
    Cust_FirstName  VARCHAR(50)  NOT NULL,
    Cust_LastName   VARCHAR(50)  NOT NULL,
    Cust_Email      VARCHAR(100) NOT NULL UNIQUE,
    Cust_Phone      CHAR(15)     NOT NULL,
    Cust_Address    VARCHAR(255) NOT NULL,
    Cust_Password   VARCHAR(255) NOT NULL,
    Cust_CreatedDate DATETIME    DEFAULT CURRENT_TIMESTAMP
);

-- 2. Branch
CREATE TABLE Branch (
    Brnch_ID            INT AUTO_INCREMENT PRIMARY KEY,
    Brnch_Name          VARCHAR(100) NOT NULL,
    Brnch_Location      VARCHAR(255) NOT NULL,
    Brnch_ContactNumber CHAR(15)     NOT NULL,
    Brnch_ServiceRadius DECIMAL(5,2) NOT NULL,
    Brnch_OpeningTime   TIME         NOT NULL,
    Brnch_ClosingTime   TIME         NOT NULL
);

-- 3. Promotion
CREATE TABLE Promotion (
    Promo_ID            INT AUTO_INCREMENT PRIMARY KEY,
    Promo_Code          VARCHAR(50)  NOT NULL UNIQUE,
    Promo_Description   VARCHAR(255) NOT NULL,
    Promo_Discount      ENUM('Percentage','Fixed') NOT NULL,
    Promo_DiscountValue DECIMAL(10,2) NOT NULL,
    Promo_ValidFrom     DATE NOT NULL,
    Promo_ValidTo       DATE NOT NULL,
    Promo_Category      VARCHAR(50) NOT NULL
);

-- 4. Product
CREATE TABLE Product (
    Prod_ID       INT AUTO_INCREMENT PRIMARY KEY,
    Prod_Name     VARCHAR(100) NOT NULL,
    Prod_Category VARCHAR(50)  NOT NULL,
    Prod_BasePrice DECIMAL(10,2) NOT NULL CHECK (Prod_BasePrice >= 0),
    Prod_Type     VARCHAR(50)  NOT NULL,
    Prod_Status   ENUM('Available','Unavailable') NOT NULL DEFAULT 'Available',
    Prod_Image    VARCHAR(255)
);

-- 5. Order
CREATE TABLE `Order` (
    Order_ID            INT AUTO_INCREMENT PRIMARY KEY,
    Order_Date          DATETIME      DEFAULT CURRENT_TIMESTAMP,
    Order_KitchenStartTime DATETIME,
    Order_Status        VARCHAR(30)   NOT NULL DEFAULT 'Pending',
    Order_TotalAmount   DECIMAL(10,2) NOT NULL CHECK (Order_TotalAmount >= 0),
    Order_DeliveryAddress VARCHAR(255) NOT NULL,
    Order_DeliveryFee   DECIMAL(10,2) NOT NULL CHECK (Order_DeliveryFee >= 0),
    Order_CustID        INT NOT NULL,
    Order_BrnchID       INT,
    Order_PromoID       INT,
    FOREIGN KEY (Order_CustID)  REFERENCES Customer(Cust_ID)  ON DELETE CASCADE,
    FOREIGN KEY (Order_BrnchID) REFERENCES Branch(Brnch_ID)   ON DELETE SET NULL,
    FOREIGN KEY (Order_PromoID) REFERENCES Promotion(Promo_ID) ON DELETE SET NULL
);

-- 6. Order_Item
CREATE TABLE Order_Item (
    OItem_ID          INT AUTO_INCREMENT PRIMARY KEY,
    OItem_Qty         INT           NOT NULL CHECK (OItem_Qty >= 1),
    OItem_CrustType   ENUM('Thin Crust','Hand Tossed') DEFAULT 'Thin Crust',
    OItem_UnitPrice   DECIMAL(10,2) NOT NULL CHECK (OItem_UnitPrice >= 0),
    OItem_AddToppings DECIMAL(10,2) DEFAULT 0.00,
    OItem_Instruction VARCHAR(255),
    OItem_OrderID     INT NOT NULL,
    OItem_ProdID      INT NOT NULL,
    FOREIGN KEY (OItem_OrderID) REFERENCES `Order`(Order_ID) ON DELETE CASCADE,
    FOREIGN KEY (OItem_ProdID)  REFERENCES Product(Prod_ID)  ON DELETE CASCADE
);

-- 7. Employee
CREATE TABLE Employee (
    Emp_ID        INT AUTO_INCREMENT PRIMARY KEY,
    Emp_FirstName VARCHAR(100) NOT NULL,
    Emp_LastName  VARCHAR(100) NOT NULL,
    Emp_Phone     VARCHAR(20)  NOT NULL,
    Emp_Role      VARCHAR(100) NOT NULL,
    Emp_BrnchID   INT NOT NULL,
    FOREIGN KEY (Emp_BrnchID) REFERENCES Branch(Brnch_ID) ON DELETE CASCADE
);

-- 8. Booking
CREATE TABLE Booking (
    Bokng_ID            INT AUTO_INCREMENT PRIMARY KEY,
    Bokng_SchedDateTime DATETIME NOT NULL,
    Bokng_Status        VARCHAR(50) NOT NULL DEFAULT 'Pending',
    Bokng_CreatedAt     DATETIME DEFAULT CURRENT_TIMESTAMP,
    Bokng_ModifiedAt    DATETIME,
    Bokng_CancelledAt   DATETIME,
    Bokng_CustID        INT NOT NULL,
    Bokng_OrderID       INT,
    FOREIGN KEY (Bokng_CustID)  REFERENCES Customer(Cust_ID) ON DELETE CASCADE,
    FOREIGN KEY (Bokng_OrderID) REFERENCES `Order`(Order_ID) ON DELETE SET NULL
);

-- 9. Payment
CREATE TABLE Payment (
    Pay_ID          INT AUTO_INCREMENT PRIMARY KEY,
    Pay_Method      VARCHAR(50)   NOT NULL,
    Pay_Status      VARCHAR(30)   NOT NULL DEFAULT 'Pending',
    Pay_Amount      DECIMAL(10,2) NOT NULL CHECK (Pay_Amount >= 0),
    Pay_Refund      DECIMAL(10,2) DEFAULT 0.00,
    Pay_CODReceived BOOLEAN DEFAULT FALSE,
    Pay_DateTime    DATETIME DEFAULT CURRENT_TIMESTAMP,
    Pay_OrderID     INT NOT NULL,
    FOREIGN KEY (Pay_OrderID) REFERENCES `Order`(Order_ID) ON DELETE CASCADE
);

-- 10. Rider
CREATE TABLE Rider (
    Rider_ID            INT AUTO_INCREMENT PRIMARY KEY,
    Rider_FirstName     VARCHAR(50) NOT NULL,
    Rider_LastName      VARCHAR(50) NOT NULL,
    Rider_ContactNumber CHAR(15)    NOT NULL,
    Rider_Status        ENUM('Available','Unavailable') NOT NULL DEFAULT 'Available'
);

-- 11. Delivery
CREATE TABLE Delivery (
    Dlvry_ID            INT AUTO_INCREMENT PRIMARY KEY,
    Dlvry_PickTime      DATETIME,
    Dlvry_DeliveryTime  DATETIME,
    Dlvry_Status        VARCHAR(30) NOT NULL DEFAULT 'Pending',
    Dlvry_EstimatedTime DATETIME,
    Dlvry_OrderID       INT NOT NULL,
    Dlvry_RiderID       INT NOT NULL,
    FOREIGN KEY (Dlvry_OrderID)  REFERENCES `Order`(Order_ID) ON DELETE CASCADE,
    FOREIGN KEY (Dlvry_RiderID)  REFERENCES Rider(Rider_ID)   ON DELETE CASCADE
);

-- 12. OrderStatusLog
CREATE TABLE OrderStatusLog (
    OrdLg_ID        INT AUTO_INCREMENT PRIMARY KEY,
    OrdLg_Status    VARCHAR(30) NOT NULL,
    OrdLg_ChangedBy VARCHAR(50) NOT NULL,
    OrdLg_Timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    OrdLg_OrderID   INT NOT NULL,
    FOREIGN KEY (OrdLg_OrderID) REFERENCES `Order`(Order_ID) ON DELETE CASCADE
);

-- ============================================================
-- SAMPLE DATA
-- ============================================================

INSERT INTO Branch (Brnch_Name, Brnch_Location, Brnch_ContactNumber, Brnch_ServiceRadius, Brnch_OpeningTime, Brnch_ClosingTime) VALUES
('Shakey\'s SM City Cebu',     'SM City Cebu, North Reclamation Area, Cebu City', '032-231-1234', 5.00, '10:00:00', '22:00:00'),
('Shakey\'s Ayala Center Cebu','Ayala Center Cebu, Cebu Business Park, Cebu City','032-231-5678', 5.00, '10:00:00', '22:00:00'),
('Shakey\'s IT Park',          'Cybergate Tower, IT Park, Lahug, Cebu City',       '032-232-9012', 4.50, '10:00:00', '23:00:00');

INSERT INTO Promotion (Promo_Code, Promo_Description, Promo_Discount, Promo_DiscountValue, Promo_ValidFrom, Promo_ValidTo, Promo_Category) VALUES
('HBO999',      'Home Bonding Offer – 1 Large Pizza + 4pcs Chicken + Garlic Bread + 1.5L Coke', 'Fixed',      689.00, '2025-01-01', '2025-12-31', 'Bundle'),
('GRAD999',     'Graduation Meal Deal – Pizza + Chicken + Drinks + Coupons',                   'Fixed',      200.00, '2025-03-01', '2025-06-30', 'Bundle'),
('SUPER10',     'Supercard Exclusive – 10% off on all orders',                                 'Percentage',  10.00, '2025-01-01', '2025-12-31', 'Supercard'),
('WELCOME50',   'Welcome discount for new customers',                                          'Fixed',       50.00, '2025-01-01', '2025-12-31', 'All');

INSERT INTO Product (Prod_Name, Prod_Category, Prod_BasePrice, Prod_Type, Prod_Status) VALUES
("Manager's Choice",           'Pizza',          399.00, 'Pizza',    'Available'),
('Pepperoni',                  'Pizza',          349.00, 'Pizza',    'Available'),
('Hawaiian',                   'Pizza',          329.00, 'Pizza',    'Available'),
('Cheese Lovers',              'Pizza',          379.00, 'Pizza',    'Available'),
('Spicy Veggie',               'Pizza',          299.00, 'Pizza',    'Available'),
('Bacon BBQ',                  'Pizza',          389.00, 'Pizza',    'Available'),
("Chicken 'N Mojos",           "Chicken 'N Mojos", 189.00, 'Chicken', 'Available'),
('Mojos',                      'Sides',           99.00, 'Sides',   'Available'),
('Cheesy Garlic Bread',        'Sides',           89.00, 'Sides',   'Available'),
('Spaghetti',                  'Pasta',          149.00, 'Pasta',   'Available'),
('Carbonara',                  'Pasta',          159.00, 'Pasta',   'Available'),
('Parmigiana Chicken Royale',  'Group Meals',    269.00, 'Chicken', 'Available'),
('Truffle Mushroom Chicken',   'Group Meals',    279.00, 'Chicken', 'Available'),
('HBO Bundle',                 'Promos',         999.00, 'Bundle',  'Available'),
('Coke 1.5L',                  'Drinks',          89.00, 'Beverage','Available'),
('Royal 1.5L',                 'Drinks',          89.00, 'Beverage','Available'),
('Spaghetti & Chicken Combo',  'Combos',         229.00, 'Combo',     'Available'),
('Mojos & Chicken Combo',      'Combos',         219.00, 'Combo',     'Available'),
('Pizza Slice & Soda Combo',   'Combos',         149.00, 'Combo',     'Available'),
('Classic Hero Sandwich',      'Hero Sandwiches', 199.00,'Sandwich',  'Available'),
('Roast Beef Hero',            'Hero Sandwiches', 219.00,'Sandwich',  'Available'),
('Chicken Hero Sandwich',      'Hero Sandwiches', 209.00,'Sandwich',  'Available'),
('S\'mores Pizza',             'Desserts',       249.00, 'Dessert',   'Available'),
('Chocolate Lava Cake',        'Desserts',       129.00, 'Dessert',   'Available'),
('Bibingka Cheesecake',        'Desserts',       159.00, 'Dessert',   'Available'),
('Onion Rings',                'Starters',        99.00, 'Sides',     'Available'),
('Mozzarella Sticks',          'Starters',       139.00, 'Sides',     'Available'),
('Captain\'s Choice Platter',  'Starters',       299.00, 'Sides',     'Available'),
('Tuna Caesar Salad',          'Soup & Salad',   189.00, 'Salad',     'Available'),
('Garden Salad',               'Soup & Salad',   149.00, 'Salad',     'Available'),
('Cream of Mushroom Soup',     'Soup & Salad',   119.00, 'Soup',      'Available'),
('Extra Rice',                 'Extras',          39.00, 'Sides',     'Available'),
('Extra Gravy',                'Extras',          25.00, 'Sides',     'Available'),
('Extra Mojo Sauce',           'Extras',          25.00, 'Sides',     'Available');

INSERT INTO Rider (Rider_FirstName, Rider_LastName, Rider_ContactNumber, Rider_Status) VALUES
('Juan', 'Dela Cruz',  '+639171234567', 'Available'),
('Pedro','Santos',     '+639281234567', 'Available'),
('Maria','Reyes',      '+639391234567', 'Unavailable');
