<?php
namespace Controllers\admin;
use Controller;

class Rooms extends Controller {
    public function index() {

        $this->view('layout/admin/admin', [
            'viewFile' => '../../admin/rooms.php',
        ]);
    }
}