<?php

namespace Controllers\admin;

use Controller;
require_once __DIR__ . '/../../models/bookingModel.php';
require_once __DIR__ . '/../../models/hotelsModel.php';
require_once __DIR__ . '/../../models/partnersModel.php';


class Dashboard extends Controller
{
    public function index()
    {

        $bookingModel = new \BookingModel();
        $hotelsModel = new \HotelsModel();
        $partnerModel = new \PartnersModel();

        $totalPlatformFee = $bookingModel->getTotalPlatformFee();
        $totalActiveOrders = $bookingModel->countAllOrderActive();
        $recentBookings = $bookingModel->get5LatestBookings(5); // Lấy 5 booking gần đây nhất
        $monthlyRevenue = $bookingModel->getMonthlyRevenue();
        $totalHotels = $hotelsModel->getCountHotels();
        $totalPartners = $partnerModel->getCountPartnersPending();
        
        

        // Truyền dữ liệu vào view
        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/dashboard.php',
            'totalPlatformFee' => $totalPlatformFee,
            'totalActiveOrders' => $totalActiveOrders,
            'monthlyRevenue' => $monthlyRevenue,
            'totalHotels' => $totalHotels,
            'totalPartners' => $totalPartners,
            'recentBookings' => $recentBookings

        ]);
    }
}
