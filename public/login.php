<?php
require_once __DIR__ . '/../app/core/bootstrap.php';
require_guest();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email && $password) {
        $customer = (new Customer($pdo))->authenticate($email, $password);

        if ($customer) {
            $_SESSION['cust_id']        = $customer['Cust_ID'];
            $_SESSION['cust_firstname'] = $customer['Cust_FirstName'];
            $_SESSION['cust_lastname']  = $customer['Cust_LastName'];
            $_SESSION['cust_email']     = $customer['Cust_Email'];
            $_SESSION['cust_phone']     = $customer['Cust_Phone'];
            $_SESSION['cust_address']   = $customer['Cust_Address'];
            header('Location: ' . url('/home'));
            exit;
        }
        $error = 'Invalid email or password.';
    } else {
        $error = 'Please fill in all fields.';
    }
}

view('auth/login', compact('error'));
