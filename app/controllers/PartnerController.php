<?php

class PartnerController extends Controller {

    protected $partnerHotels = [];
    protected $activeHotelId;
    
    public function __construct() {
        // Kiểm tra quyền truy cập Partner tại đây
        // if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Partner') {
        //     header('Location: ' . URLROOT . '/login');
        //     exit;
        // }

        // if ($_SESSION['user_role'] == 'Staff') {
        //     header('Location: ' . URLROOT . '/manage/'.$_SESSION['active_hotel_id']);
        //     exit;
        // }
        // else{
            $this->partnerHotels = $this->service('PortfolioService')->getHotelsByPartner(2);
            $this->activeHotelId = $_SESSION['active_hotel_id'] ?? null;
        // }
    }

    public function manage($id) {
        $_SESSION['active_hotel_id'] = $id;
        foreach ($this->partnerHotels as $hotel) {
            if ($hotel['id'] == $id) {
                $_SESSION['active_hotel_name'] = $hotel['hotelName'];
                break;
            }
        }
        session_write_close();
        header('Location: ' . URLROOT . '/dashboard');
        exit;
    }

    public function index() {
        $partnerId = 2;
        $portfolioService = $this->service('PortfolioService');
        $data = $portfolioService->getDashboardData($partnerId);
        $data['partnerHotels']= $this->partnerHotels;
        $data['hideSidebar'] = true;
        $this->viewPartner('globalportfoliodashboard', $data);
    }
}