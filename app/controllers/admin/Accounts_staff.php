<?php
namespace Controllers\admin;
use Controller;

    class Accounts_staff extends Controller {
        public function index() {
            $this->view('admin/accounts_staff'); // chỉ render nội dung accounts_staff
        }
    }
