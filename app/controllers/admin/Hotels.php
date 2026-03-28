<?php
namespace Controllers\admin;
use Controller;

class Hotels extends Controller {
    public function index() {

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/hotels.php',
        ]);
    }
}