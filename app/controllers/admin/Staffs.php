<?php
namespace Controllers\admin;
use Controller;

class Staffs extends Controller {
    public function index() {

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/staffs.php',
        ]);
    }
}