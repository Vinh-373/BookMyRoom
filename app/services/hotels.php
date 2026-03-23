<?php
class hotelsService extends Service {
    private $hotelsModel;

    public function __construct() {
        $this->hotelsModel = new hotelsModel();
    }

    public function getAllHotels() {
        return $this->hotelsModel->select_array();
    }

    // Thêm các phương thức khác như getHotelById, createHotel, updateHotel, deleteHotel nếu cần
    //Các hàm này sẽ được gọi từ controller để xử lý logic nghiệp vụ liên quan đến khách sạn
}