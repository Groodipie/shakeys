<?php
function require_login(): void {
    if (!isset($_SESSION['cust_id'])) {
        header('Location: login.php');
        exit;
    }
}

function require_guest(): void {
    if (isset($_SESSION['cust_id'])) {
        header('Location: home.php');
        exit;
    }
}
