<?php

namespace Controllers\admin;

use Controller;

class Settings extends Controller
{
    public function index()
    {
        if (isset($_GET['partial']) && $_GET['partial'] == '1') {
            $this->view('admin/settings');
            return;
        }

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/settings.php',
        ]);
    }
}
