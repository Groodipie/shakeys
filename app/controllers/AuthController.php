<?php
class AuthController extends Controller {
    public function login(): void {
        require_guest();

        $pageTitle = "Login — Shakey's Delivery";
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($email && $password) {
                $customer = (new Customer($this->pdo))->authenticate($email, $password);

                if ($customer) {
                    $_SESSION['cust_id']        = $customer['Cust_ID'];
                    $_SESSION['cust_firstname'] = $customer['Cust_FirstName'];
                    $_SESSION['cust_lastname']  = $customer['Cust_LastName'];
                    $_SESSION['cust_email']     = $customer['Cust_Email'];
                    $_SESSION['cust_phone']     = $customer['Cust_Phone'];
                    $_SESSION['cust_address']   = $customer['Cust_Address'];
                    $this->redirect('/home');
                }
                $error = 'Invalid email or password.';
            } else {
                $error = 'Please fill in all fields.';
            }
        }

        partial('header', ['pageTitle' => $pageTitle]);
        view('auth/login', compact('error'));
        partial('footer');
    }

    public function register(): void {
        require_guest();

        $pageTitle = "Create Account — Shakey's Delivery";
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
                $customers = new Customer($this->pdo);
                if ($customers->findByEmail($email)) {
                    $error = 'This email is already registered. <a href="' . e(url('/login')) . '" style="color:var(--sk-red);">Login here</a>.';
                } else {
                    $customers->create([
                        'first_name' => $first,
                        'last_name'  => $last,
                        'email'      => $email,
                        'phone'      => '+63' . $phone,
                        'address'    => '',
                        'password'   => $pass,
                    ]);
                    $success = 'Account created! <a href="' . e(url('/login')) . '" style="color:var(--sk-red);font-weight:700;">Login now</a>.';
                }
            }
        }

        partial('header', ['pageTitle' => $pageTitle]);
        view('auth/register', compact('error', 'success'));
        partial('footer');
    }

    public function forgotPassword(): void {
        $pageTitle = "Forgot Password — Shakey's Delivery";
        $sent = false;
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            if ($email) {
                (new Customer($this->pdo))->findByEmail($email);
                $sent = true;
            } else {
                $error = 'Please enter your email address.';
            }
        }

        partial('header', ['pageTitle' => $pageTitle]);
        view('auth/forgot_password', compact('sent', 'error'));
        partial('footer');
    }

    public function logout(): void {
        session_destroy();
        $this->redirect('/login');
    }
}
