<?php
namespace Controllers\admin;
use Controller;

class Settings extends Controller {
    public function index() {

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/settings.php',
        ]);
    }
}