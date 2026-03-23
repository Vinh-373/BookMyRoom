<?php
namespace Controllers\admin;
use Controller;

class Dashboard extends Controller {
    public function index() {
        $viewFile = './app/views/admin/home.php';
        $this->view('layout/admin/admin', [ // gọi đúng layout
            'viewFile' => $viewFile
        ]);
    }
}