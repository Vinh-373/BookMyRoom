<?php
namespace Controllers\admin;
use Controller;

    class Home extends Controller {
        public function index() {
            $this->view('admin/home'); // chỉ render nội dung home
        }
    }
