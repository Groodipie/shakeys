<?php
// includes/auth_check.php — Redirect to login if not authenticated
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['cust_id'])) {
    header('Location: ../login.php');
    exit;
}
