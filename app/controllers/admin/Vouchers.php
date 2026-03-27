<?php
namespace Controllers\admin;
use Controller;

class Vouchers extends Controller {
    public function index() {

        $this->view('layout/admin/admin', [
            'viewFile' => '../../admin/vouchers.php',
        ]);
    }
}