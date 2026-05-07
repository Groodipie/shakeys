<?php
require_once __DIR__ . '/../app/core/bootstrap.php';
require_guest();

$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first   = trim($_POST['first_name']  ?? '');
    $last    = trim($_POST['last_name']   ?? '');
    $phone   = trim($_POST['phone']       ?? '');
    $email   = trim($_POST['email']       ?? '');
    $pass    = $_POST['password']         ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $agree   = isset($_POST['agree']);

    if (!$first || !$last || !$phone || !$email || !$pass) {
        $error = 'Please fill in all required fields.';
    } elseif ($pass !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($pass) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif (!$agree) {
        $error = 'You must agree to the Terms and Conditions.';
    } else {
        $customers = new Customer($pdo);
        if ($customers->findByEmail($email)) {
            $error = 'This email is already registered. <a href="/login" style="color:var(--sk-red);">Login here</a>.';
        } else {
            $customers->create([
                'first_name' => $first,
                'last_name'  => $last,
                'email'      => $email,
                'phone'      => '+63' . $phone,
                'address'    => '',
                'password'   => $pass,
            ]);
            $success = 'Account created! <a href="/login" style="color:var(--sk-red);font-weight:700;">Login now</a>.';
        }
    }
}

view('auth/register', compact('error', 'success'));
