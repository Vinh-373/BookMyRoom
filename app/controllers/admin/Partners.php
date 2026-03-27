<?php
namespace Controllers\admin;
use Controller;

class Partners extends Controller {
    public function index() {

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/partners.php',
        ]);
    }
}