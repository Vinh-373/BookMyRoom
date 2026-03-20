<?php
namespace Controllers\admin;
use Controller;
class Dashboard extends Controller {
    public function index() {
        $viewFile = './app/views/admin/dashboard.php';
        // $this->view('customer/booking/index');
         $this->view('layout/admin/admin',[
            'viewFile' => $viewFile
         ]);

    }
}