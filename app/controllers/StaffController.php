<?php
class StaffController extends Controller {
    public function dashboard(): void {
        $this->render('dashboard', 'Dashboard');
    }

    public function orders(): void {
        $this->guard();
        $branchId = (int)($_SESSION['staff_branch_id'] ?? 0);
        $orders   = (new Order($this->pdo))->listForBranch($branchId);
        $riders   = (new Rider($this->pdo))->available();
        $flash    = $this->takeFlash();
        $this->render('orders', 'Orders', compact('orders', 'riders', 'flash'));
    }

    public function assignRider(string $id): void {
        $this->guard();
        $riderId = (int)($_POST['rider_id'] ?? 0);
        if ($riderId <= 0) {
            $_SESSION['staff_flash'] = ['type' => 'error', 'msg' => 'Please select a rider.'];
            $this->redirect('/staff/orders');
            return;
        }
        $changedBy = 'Staff: ' . ($_SESSION['staff_name'] ?? 'unknown');
        (new Order($this->pdo))->assignRider((int)$id, $riderId, $changedBy);
        $_SESSION['staff_flash'] = ['type' => 'success', 'msg' => 'Rider assigned.'];
        $this->redirect('/staff/orders');
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

    private function takeFlash(): ?array {
        $flash = $_SESSION['staff_flash'] ?? null;
        unset($_SESSION['staff_flash']);
        return $flash;
    }

    private function render(string $view, string $title, array $data = []): void {
        $this->guard();
        partial('staff_header', ['current' => $view, 'pageTitle' => $title . " — Shakey's Staff"]);
        view('staff/' . $view, $data);
        partial('staff_footer');
    }
}
