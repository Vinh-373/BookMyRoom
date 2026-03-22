<?php
namespace Controllers\admin;
use Controller;

    class Accounts_partner extends Controller {
        public function index() {
            $this->view('admin/accounts_partner'); // chỉ render nội dung accounts_partner
        }
    }
