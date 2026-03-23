<?php
namespace Controllers\admin;
use Controller;

    class Hotels extends Controller {
        public function index() {
            $this->view('admin/hotels'); // chỉ render nội dung hotels
        }
    }
