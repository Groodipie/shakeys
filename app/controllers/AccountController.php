<?php
class AccountController extends Controller {
    public function index(): void {
        require_login();

        $pageTitle = "My Account — Shakey's Delivery";
        $custId    = (int)$_SESSION['cust_id'];
        $success   = $error = '';

        $customers = new Customer($this->pdo);
        $orders    = new Order($this->pdo);
        $cust      = $customers->findById($custId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] === 'update_profile') {
                $first   = trim($_POST['first_name'] ?? '');
                $last    = trim($_POST['last_name']  ?? '');
                $phone   = trim($_POST['phone']      ?? '');
                $address = trim($_POST['address']    ?? '');

                if ($first && $last && $phone && $address) {
                    $customers->updateProfile($custId, $first, $last, $phone, $address);
                    $_SESSION['cust_firstname'] = $first;
                    $_SESSION['cust_lastname']  = $last;
                    $success = 'Profile updated successfully.';
                    $cust = $customers->findById($custId);
                } else {
                    $error = 'Please fill in all fields.';
                }
            }

            if ($_POST['action'] === 'change_password') {
                $current = $_POST['current_password'] ?? '';
                $new     = $_POST['new_password']     ?? '';
                $confirm = $_POST['confirm_password'] ?? '';

                if (!password_verify($current, $cust['Cust_Password'])) {
                    $error = 'Current password is incorrect.';
                } elseif (strlen($new) < 6) {
                    $error = 'New password must be at least 6 characters.';
                } elseif ($new !== $confirm) {
                    $error = 'Passwords do not match.';
                } else {
                    $customers->updatePassword($custId, $new);
                    $success = 'Password changed successfully.';
                }
            }
        }

        $totalOrders = $orders->countForCustomer($custId);

        partial('header', ['pageTitle' => $pageTitle]);
        view('account', compact('cust', 'success', 'error', 'totalOrders'));
        partial('footer');
    }
}
