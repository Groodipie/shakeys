<?php
class AdminController extends Controller {
    public function dashboard(): void { $this->render('dashboard', 'Dashboard'); }
    public function staff():     void { $this->render('staff',     'Staff Management'); }
    public function riders():    void { $this->render('riders',    'Rider Management'); }
    public function products():  void { $this->render('products',  'Add Product'); }

    public function logout(): void {
        unset($_SESSION['admin_logged_in'], $_SESSION['admin_username']);
        $this->redirect('/admin/login');
    }

    private function render(string $view, string $title): void {
        if (empty($_SESSION['admin_logged_in'])) {
            $this->redirect('/admin/login');
        }
        partial('admin_header', ['current' => $view, 'pageTitle' => $title . " — Shakey's Admin"]);
        view('admin/' . $view);
        partial('admin_footer');
    }
}
