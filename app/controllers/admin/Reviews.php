<?php
namespace Controllers\admin;
use Controller;

class Reviews extends Controller {
    public function index() {

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/reviews.php',
        ]);
    }
}