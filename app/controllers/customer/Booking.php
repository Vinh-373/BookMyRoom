<?php
namespace Controllers\customer;
use Controller;
class Booking extends Controller {
    public function index() {
        $this->view('customer/booking/index');
    }
    public function hotels() {
        $this->view('customer/booking/hotels');
    }   
}