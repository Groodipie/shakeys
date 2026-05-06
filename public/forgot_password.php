<?php
require_once __DIR__ . '/../app/core/bootstrap.php';

$sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if ($email) {
        // Always show success to prevent email enumeration
        (new Customer($pdo))->findByEmail($email);
        $sent = true;
    } else {
        $error = 'Please enter your email address.';
    }
}

view('auth/forgot_password', compact('sent', 'error'));
