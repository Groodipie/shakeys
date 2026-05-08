<?php
function require_login(): void {
    if (!isset($_SESSION['cust_id'])) {
        header('Location: ' . url('/login'));
        exit;
    }
}

function require_guest(): void {
    if (isset($_SESSION['cust_id'])) {
        header('Location: ' . url('/home'));
        exit;
    }
}
