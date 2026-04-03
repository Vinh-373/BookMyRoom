<?php

namespace Controllers\customer;

use Controller;
use Services\HotelService;

require_once "./app/services/hotelService.php";
class Search extends Controller

{
    private $hotelsService;
    public function __construct()
    {
        $this->hotelsService = new HotelService();
    }
    public function hotels($page = 1)
    {
        $location = $_GET['location'] ?? null; // "Ho-Chi-Minh"
        $dates = $_GET['dates'] ?? null;
        $location = str_replace('+', ' ', $location); // "Ho Chi Minh"
        $sortBy = $_GET['sortBy'] ?? 'h.id';
        $sortOrder = $_GET['sortOrder'] ?? 'DESC';
        $priceRange = $_GET['priceRange'] ?? null;

        $limit = 5;
        $page = (int)$page;
        if ($page < 1) $page = 1;

        $offset = ($page - 1) * $limit;

        // 👉 Lấy dữ liệu theo trang
        $hotelsOnPage = $this->hotelsService->getAllHotels($location, $limit, $offset, $sortBy, $sortOrder, $dates, $priceRange);
        if (!empty($hotelsOnPage) && $hotelsOnPage[0]['hotelName'] === $location) {
            header("Location: http://localhost/BookMyRoom/booking/hotel/" . $hotelsOnPage[0]['id']);
            exit();
        }
        // 👉 Đếm tổng
        $totalHotels = $this->hotelsService->countHotels($location);
        $totalPages = ceil($totalHotels / $limit);

        $viewFile = './app/views/customer/search/hotelsPage.php';
        $this->view('layout/customer/client', [
            'viewFile' => $viewFile,
            'hotels' => $hotelsOnPage,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'filters' => [
                'location' => $location,
                'dates' => $dates
            ]
        ]);
    }
}
