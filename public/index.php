<?php
require_once __DIR__ . '/../app/core/bootstrap.php';
header('Location: ' . (isset($_SESSION['cust_id']) ? 'home.php' : 'login.php'));
exit;
