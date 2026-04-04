<?php
namespace Controllers\admin;
use Controller;

class Bookings extends Controller {
    public function index() {


    
        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/bookings.php',
        ]);
    }
}