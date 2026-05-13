<?php
class StaffAuthController extends Controller {
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empId = (int)($_POST['employee_id'] ?? 0);
            $phone = trim($_POST['password'] ?? '');

            if ($empId > 0 && $phone !== '') {
                $emp = (new Employee($this->pdo))->findByCredentials($empId, $phone);
                if ($emp) {
                    $_SESSION['staff_logged_in'] = true;
                    $_SESSION['staff_id']        = (int)$emp['Emp_ID'];
                    $_SESSION['staff_name']      = $emp['Emp_FirstName'] . ' ' . $emp['Emp_LastName'];
                    $_SESSION['staff_role']      = $emp['Emp_Role'];
                    $_SESSION['staff_branch_id'] = (int)$emp['Emp_BrnchID'];
                    $_SESSION['staff_branch']    = $emp['Brnch_Name'];
                    $this->redirect('/staff/dashboard');
                }
            }
            $this->redirect('/staff/login');
            return;
        }

        view('auth/staff_login');
    }
}
