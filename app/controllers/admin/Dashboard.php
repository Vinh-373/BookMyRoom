<?php

namespace Controllers\admin;

use Controller;
use Models\HotelsModel;
use Models\PartnersModel;
use Models\BookingModel;
require_once "./app/models/hotelsModel.php";
require_once "./app/models/partnersModel.php";
require_once "./app/models/bookingModel.php";




class Dashboard extends Controller
{
    public function index()
    {

        $bookingModel = new BookingModel();
        $hotelsModel = new HotelsModel();
        $partnerModel = new PartnersModel();

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
