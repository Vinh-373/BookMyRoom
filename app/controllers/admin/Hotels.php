<?php
namespace Controllers\admin;
use Controller;

// Auto-load models (order matters!)
require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../models/myModels.php';
require_once __DIR__ . '/../../models/hotelsModel.php';

class Hotels extends Controller {
    public function index() {
        try {
            // Khởi tạo model
            $hotelModel = new \Models\hotelsModel();
            
            // Lấy tất cả khách sạn với thông tin thêm
            $hotels = $hotelModel->join_multi(
                joins: [
                    [
                        'table' => 'cities',
                        'type' => 'LEFT',
                        'on' => 'hotels.cityId = cities.id'
                    ],
                    [
                        'table' => 'users',
                        'type' => 'LEFT',
                        'on' => 'hotels.partnerId = users.id'
                    ]
                ],
                select: 'hotels.id, hotels.hotelName, hotels.rating, hotels.address, hotels.partnerId, cities.name as cityName, users.email as partnerEmail',
                where: ['hotels.deletedAt' => null],
                orderBy: 'hotels.createdAt DESC'
            );
            
            // Tính toán thống kê KPI
            $allHotels = $hotelModel->select_array('*', ['deletedAt' => null]);
            $totalHotels = count($allHotels);
            $activeHotels = count($allHotels);
            $pendingHotels = 0;
            
            // Lấy danh sách thành phố từ hotels
            $citiesFromHotels = $hotelModel->join_multi(
                joins: [
                    [
                        'table' => 'cities',
                        'type' => 'INNER',
                        'on' => 'hotels.cityId = cities.id'
                    ]
                ],
                select: 'DISTINCT cities.id, cities.name',
                where: [],
                orderBy: 'cities.name'
            );
            
            // Format cities data
            $cities = [];
            if (is_array($citiesFromHotels)) {
                foreach ($citiesFromHotels as $city) {
                    $cities[] = [
                        'id' => $city['id'] ?? 0,
                        'name' => $city['name'] ?? 'N/A'
                    ];
                }
            }
            
            // Pass dữ liệu tới view
            $this->view('admin/hotels', [
                'hotels' => $hotels ?? [],
                'totalHotels' => $totalHotels,
                'activeHotels' => $activeHotels,
                'pendingHotels' => $pendingHotels,
                'cities' => $cities
            ]);
            
        } catch (\Exception $e) {
            $this->view('admin/hotels', [
                'hotels' => [],
                'totalHotels' => 0,
                'activeHotels' => 0,
                'pendingHotels' => 0,
                'cities' => [],
                'error' => $e->getMessage()
            ]);
        }
    }
}
