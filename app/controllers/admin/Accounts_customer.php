<?php
namespace Controllers\admin;
use Controller;

    class Accounts_customer extends Controller {
        public function index() {
            $this->view('admin/accounts_customer'); // chỉ render nội dung accounts_customer
        }
    }
