<?php
namespace Controllers\admin;
use Controller;

class Dashboard extends Controller {
    public function index() {
        if (isset($_GET['partial']) && $_GET['partial'] == '1') {
            $this->view('admin/dashboard');
            return;
        }

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/dashboard.php'
        ]);
    }
}