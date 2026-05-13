<?php
class RiderAuthController extends Controller {
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $riderId = (int)($_POST['rider_id'] ?? 0);
            $phone   = trim($_POST['password'] ?? '');

            if ($riderId > 0 && $phone !== '') {
                $rider = (new Rider($this->pdo))->findByCredentials($riderId, $phone);
                if ($rider) {
                    $_SESSION['rider_logged_in'] = true;
                    $_SESSION['rider_id']        = (int)$rider['Rider_ID'];
                    $_SESSION['rider_name']      = $rider['Rider_FirstName'] . ' ' . $rider['Rider_LastName'];
                    $_SESSION['rider_phone']     = $rider['Rider_ContactNumber'];
                    $_SESSION['rider_status']    = $rider['Rider_Status'];
                    $this->redirect('/rider/dashboard');
                }
            }
            $this->redirect('/rider/login');
            return;
        }

        view('auth/rider_login');
    }
}
