<?php
class AdminAuthController extends Controller {
    private const ADMIN_USERNAME = 'admin';
    private const ADMIN_PASSWORD = 'admin123';

    public function login(): void {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($username === self::ADMIN_USERNAME && $password === self::ADMIN_PASSWORD) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username']  = $username;
                $this->redirect('/admin/dashboard');
            }
            $error = 'Invalid username or password.';
        }

        view('auth/admin_login', compact('error'));
    }
}
