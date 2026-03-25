<?php
namespace Controllers\admin;
use Controller;

    class Bookings extends Controller {
        public function index() {
            if (isset($_GET['partial']) && $_GET['partial'] == '1') {
                $this->view('admin/bookings');
                return;
            }

            $this->view('layout/admin/admin', [
                'viewFile' => './app/views/admin/bookings.php'
            ]);
        }
    }
