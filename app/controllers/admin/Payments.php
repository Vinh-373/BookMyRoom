<?php
namespace Controllers\admin;
use Controller;

    class Payments extends Controller {
        public function index() {
            $this->view('admin/payments'); // chỉ render nội dung payments
        }
    }
