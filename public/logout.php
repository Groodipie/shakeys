<?php
require_once __DIR__ . '/../app/core/bootstrap.php';
session_destroy();
header('Location: /login');
exit;
