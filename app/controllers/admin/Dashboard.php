<?php
namespace Controllers\admin;
use Controller;

class Dashboard extends Controller {
    public function index() {

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/dashboard.php',
        ]);
    }
}