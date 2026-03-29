<?php
namespace Controllers\admin;
use Controller;

class Customers extends Controller {
    public function index() {

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/customers.php',
        ]);
    }
}