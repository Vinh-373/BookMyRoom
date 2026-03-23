<?php
namespace Controllers\admin;
use Controller;

    class Bookings extends Controller {
        public function index() {
            $this->view('admin/bookings');
        }
    }
