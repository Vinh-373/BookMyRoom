<?php
namespace Controllers\admin;
use Controller;

class ReviewPartners extends Controller {
    public function index() {

        $this->view('layout/admin/admin', [
            'viewFile' => '../../admin/reviewPartners.php',
        ]);
    }
}