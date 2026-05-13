<?php
class AdminController extends Controller {
    public function dashboard(): void { $this->render('dashboard', 'Dashboard'); }

    public function staff(): void {
        $this->guard();
        $employee = new Employee($this->pdo);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->createStaff($employee);
            return;
        }

        $staff    = $employee->all();
        $branches = $employee->branches();
        $flash    = $this->takeFlash();

        $this->render('staff', 'Employee Management', compact('staff', 'branches', 'flash'));
    }

    public function updateStaff(string $id): void {
        $this->guard();
        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name'  => trim($_POST['last_name']  ?? ''),
            'phone'      => trim($_POST['phone']      ?? ''),
            'role'       => trim($_POST['role']       ?? ''),
            'branch_id'  => $_POST['branch_id']       ?? '',
        ];

        if (array_filter($data, fn($v) => $v === '' || $v === null)) {
            $_SESSION['admin_flash'] = ['type' => 'error', 'msg' => 'All fields are required.'];
            $this->redirect('/admin/staff');
            return;
        }

        (new Employee($this->pdo))->update((int)$id, $data);
        $_SESSION['admin_flash'] = ['type' => 'success', 'msg' => 'Employee updated.'];
        $this->redirect('/admin/staff');
    }

    public function deleteStaff(string $id): void {
        $this->guard();
        (new Employee($this->pdo))->delete((int)$id);
        $_SESSION['admin_flash'] = ['type' => 'success', 'msg' => 'Employee removed.'];
        $this->redirect('/admin/staff');
    }

    public function riders():    void { $this->render('riders',    'Rider Management'); }
    public function products():  void { $this->render('products',  'Add Product'); }

    public function logout(): void {
        unset($_SESSION['admin_logged_in'], $_SESSION['admin_username']);
        $this->redirect('/admin/login');
    }

    private function createStaff(Employee $employee): void {
        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name'  => trim($_POST['last_name']  ?? ''),
            'phone'      => trim($_POST['phone']      ?? ''),
            'role'       => trim($_POST['role']       ?? ''),
            'branch_id'  => $_POST['branch_id']       ?? '',
        ];

        $missing = array_filter($data, fn($v) => $v === '' || $v === null);
        if ($missing) {
            $_SESSION['admin_flash'] = ['type' => 'error', 'msg' => 'All fields are required.'];
            $this->redirect('/admin/staff');
            return;
        }

        $employee->create($data);
        $_SESSION['admin_flash'] = ['type' => 'success', 'msg' => 'Employee added.'];
        $this->redirect('/admin/staff');
    }

    private function guard(): void {
        if (empty($_SESSION['admin_logged_in'])) {
            $this->redirect('/admin/login');
        }
    }

    private function takeFlash(): ?array {
        $flash = $_SESSION['admin_flash'] ?? null;
        unset($_SESSION['admin_flash']);
        return $flash;
    }

    private function render(string $view, string $title, array $data = []): void {
        $this->guard();
        partial('admin_header', ['current' => $view, 'pageTitle' => $title . " — Shakey's Admin"]);
        view('admin/' . $view, $data);
        partial('admin_footer');
    }
}
