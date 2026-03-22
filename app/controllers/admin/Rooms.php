<?php
namespace Controllers\admin;
use Controller;

    class Rooms extends Controller {
        public function index() {
            $this->view('admin/rooms'); // chỉ render nội dung rooms
        }
    }
