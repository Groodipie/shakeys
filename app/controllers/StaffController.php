<?php
class StaffController extends Controller {
    public function dashboard(): void {
        $this->guard();
        view('staff/dashboard');
    }

    public function logout(): void {
        unset(
            $_SESSION['staff_logged_in'],
            $_SESSION['staff_id'],
            $_SESSION['staff_name'],
            $_SESSION['staff_role'],
            $_SESSION['staff_branch_id'],
            $_SESSION['staff_branch']
        );
        $this->redirect('/staff/login');
    }

    private function guard(): void {
        if (empty($_SESSION['staff_logged_in'])) {
            $this->redirect('/staff/login');
        }
    }
}
