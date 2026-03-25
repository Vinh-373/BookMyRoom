<?php
namespace Controllers\admin;
use Controller;

class Review_partners extends Controller {
    public function index() {
        if (isset($_GET['partial']) && $_GET['partial'] == '1') {
            $this->view('admin/partner_moderation');
            return;
        }

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/partner_moderation.php'
        ]);
    }
}
