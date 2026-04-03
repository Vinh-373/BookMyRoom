<?php

namespace Controllers\customer;

use Controller;

class History extends Controller

{

    public function __construct()
    {
      
    }
    function index()
    {
        $viewFile = './app/views/customer/historyPage.php';
        $this->view('layout/customer/client', [
            'viewFile' => $viewFile,
        ]);
    }
}