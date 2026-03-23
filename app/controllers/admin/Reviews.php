<?php
namespace Controllers\admin;
use Controller;

    class Reviews extends Controller {
        public function index() {
            $this->view('admin/reviews'); // chỉ render nội dung reviews
        }
    }
