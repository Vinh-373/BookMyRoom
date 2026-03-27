<?php
namespace Controllers\admin;
use Controller;

class Hotels extends Controller {
    public function index() {

        $this->view('layout/admin/admin', [
            'viewFile' => '../../admin/hotels.php',
        ]);
    }
}