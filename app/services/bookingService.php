<?php
require_once __DIR__ . '/../models/bookingModel.php';

class bookingService  {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new bookingModel();
    }

    

}