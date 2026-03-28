<?php
namespace Controllers\customer;
use Controller;
class Booking extends Controller {
    public function index() {
        $viewFile = './app/views/customer/booking/index.php';
        // $this->view('customer/booking/index');
         $this->view('layout/customer/client',[
            'viewFile' => $viewFile
         ]);

    }
    public function hotels() {
        
        $viewFile = './app/views/customer/booking/hotels.php';
        // $this->view('customer/booking/index');
         $this->view('layout/customer/client',[
            'viewFile' => $viewFile
         ]);
    }   
}