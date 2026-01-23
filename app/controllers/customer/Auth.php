<?php
namespace Controllers\customer;
use Controller;
class Auth extends Controller {
    public function login() {
        $this->view('customer/auth/login');
    }

    public function register() {
        $this->view('customer/auth/register');
    }
    public function forgot() {
        $this->view('customer/auth/forgot');
    }
}