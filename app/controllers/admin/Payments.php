<?php
namespace Controllers\admin;
use Controller;

class Payments extends Controller {
    public function index() {

        $this->view('layout/admin/admin', [
            'viewFile' => '../../admin/payments.php',
        ]);
    }
}