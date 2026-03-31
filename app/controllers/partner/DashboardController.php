<?php
require_once '../app/controllers/PartnerController.php';
class DashboardController extends PartnerController {

    public function __construct() {
        // Chạy construct của cha để kiểm tra quyền đăng nhập/role Partner
        parent::__construct();
        
        // Kiểm tra nếu chưa chọn khách sạn thì bắt quay về trang Portfolio
        if (!isset($_SESSION['active_hotel_id'])) {
            header('Location: ' . URLROOT . '/partner');
            exit;
        }
    }

    public function index() {
        $hotelId= $this->activeHotelId;
        $service = $this->service('DashboardService');
        $data = $service->getHotelDashboardFullData($hotelId);
        $data['partnerHotels'] = $this->partnerHotels;
        $data['activeHotelId'] = $this->activeHotelId;
        $this->viewPartner('dashboard', $data);
    }
}