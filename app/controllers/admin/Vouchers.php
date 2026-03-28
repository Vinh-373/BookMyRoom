<?php
namespace Controllers\admin;
use Controller;

class Vouchers extends Controller {
    public function index() {

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/vouchers.php',
        ]);
    }
}