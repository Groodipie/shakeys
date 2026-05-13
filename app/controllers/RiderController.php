<?php
class RiderController extends Controller {
    public function dashboard(): void {
        $this->render('dashboard', 'Dashboard');
    }

    public function orders(): void {
        $this->guard();
        $riderId = (int)($_SESSION['rider_id'] ?? 0);
        $orders  = (new Order($this->pdo))->listForRider($riderId);
        $flash   = $this->takeFlash();
        $this->render('orders', 'Assigned Orders', compact('orders', 'flash'));
    }

    public function markOutForDelivery(string $id): void {
        $this->guard();
        $riderId   = (int)($_SESSION['rider_id'] ?? 0);
        $changedBy = 'Rider: ' . ($_SESSION['rider_name'] ?? 'unknown');
        $ok = (new Order($this->pdo))->markInTransit((int)$id, $riderId, $changedBy);
        $_SESSION['rider_flash'] = $ok
            ? ['type' => 'success', 'msg' => 'Order marked as out for delivery.']
            : ['type' => 'error',   'msg' => 'Unable to update this order.'];
        $this->redirect('/rider/orders');
    }

    public function markDelivered(string $id): void {
        $this->guard();
        $riderId   = (int)($_SESSION['rider_id'] ?? 0);
        $changedBy = 'Rider: ' . ($_SESSION['rider_name'] ?? 'unknown');
        $ok = (new Order($this->pdo))->markDelivered((int)$id, $riderId, $changedBy);
        $_SESSION['rider_flash'] = $ok
            ? ['type' => 'success', 'msg' => 'Order marked as delivered.']
            : ['type' => 'error',   'msg' => 'Unable to update this order.'];
        $this->redirect('/rider/orders');
    }

    public function logout(): void {
        unset(
            $_SESSION['rider_logged_in'],
            $_SESSION['rider_id'],
            $_SESSION['rider_name'],
            $_SESSION['rider_phone'],
            $_SESSION['rider_status']
        );
        $this->redirect('/rider/login');
    }

    private function guard(): void {
        if (empty($_SESSION['rider_logged_in'])) {
            $this->redirect('/rider/login');
        }
    }

    private function takeFlash(): ?array {
        $flash = $_SESSION['rider_flash'] ?? null;
        unset($_SESSION['rider_flash']);
        return $flash;
    }

    private function render(string $view, string $title, array $data = []): void {
        $this->guard();
        partial('rider_header', ['current' => $view, 'pageTitle' => $title . " — Shakey's Rider"]);
        view('rider/' . $view, $data);
        partial('rider_footer');
    }
}
