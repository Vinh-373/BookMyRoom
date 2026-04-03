<?php
namespace Services;
use Models\HotelImagesModel;
require_once "./app/models/hotelImagesModel.php";
class HotelsImagesService {
    private $hotelImagesModel;

    public function __construct() {
        $this->hotelImagesModel = new HotelImagesModel();
    }

    function getAllImagesByHotelId($hotelId){
        return $this->hotelImagesModel->select_array('*',['hotelId' => $hotelId]);
    }
}