<?php
require_once __DIR__ . '/../core/Service.php';
class DashboardService extends Service {
    public function getHotelDashboardFullData($hotelId) {
        $hotelModel = $this->model('HotelModel');
        
        $hotel = $hotelModel->getHotelById($hotelId);
        $revenueTrends = $hotelModel->getDailyRevenueLast30Days($hotelId);
        $sources = $hotelModel->getBookingSources($hotelId);
        $activities = $hotelModel->getRecentActivity($hotelId);
        // Tổng hợp chỉ số 4 thẻ trên cùng
        $stats = [
            'arrivals'   => $hotelModel->getTodayArrivals($hotelId),
            'departures' => $hotelModel->getTodayDepartures($hotelId),
            'occupancy'  => $this->calculateOccupancy($hotelId),
            'alerts'     => 5 // Có thể viết hàm đếm các booking 'PENDING'
        ];
        
        return [
            'hotel' => $hotel,
            'stats' => $stats,
            'activities' => $activities,
            'sources' => $sources,
            'revenueTrends' => $revenueTrends
        ];
    }

    private function calculateOccupancy($hotelId) {
        $hotelModel = $this->model('HotelModel');
        $data = $hotelModel->getOccupancyData($hotelId);
        if ($data['total_count'] > 0) {
            return round(($data['occupied_count'] / $data['total_count']) * 100);
        }
        return 0;
    }
}