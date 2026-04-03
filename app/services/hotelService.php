<?php

namespace Services;

use Models\HotelModel;

require_once "./app/models/hotelModel.php";
class HotelService
{
    private $hotelsModel;

    public function __construct()
    {
        $this->hotelsModel = new HotelModel();
    }

    public function getHotelById($hotelId)
    {
        return $this->hotelsModel->getHotelById($hotelId);
    }
    public function getAllHotels($location, $limit, $offset,$orderBy,$orderDir,$dates)
    {
        return $this->hotelsModel->getAllHotels($location, $limit, $offset,$orderBy,$orderDir,$dates);
    }

    public function countHotels($location)
    {
        return $this->hotelsModel->countHotels($location);
    }


    // Thêm các phương thức khác như getHotelById, createHotel, updateHotel, deleteHotel nếu cần
    //Các hàm này sẽ được gọi từ controller để xử lý logic nghiệp vụ liên quan đến khách sạn
}
