<?php

namespace Controllers\admin;

use Controller;
use Models\partnersModel;
use Models\hotelsModel;
use Models\myModels;


require_once "./app/models/partnersModel.php";
require_once "./app/models/hotelsModel.php";
require_once "./app/models/myModels.php";
class Hotels extends Controller
{
   public function index()
{
  

    $partnersModel = new partnersModel();
    $partners = $partnersModel->findAll();

    $hotelsModel = new hotelsModel();

    $hotels = $hotelsModel->join_multi(
        joins: [
            // ===== LOCATION =====
            [
                'table' => 'cities',
                'type'  => 'LEFT',
                'on'    => 'hotels.cityId = cities.id'
            ],
            [
                'table' => 'wards',
                'type'  => 'LEFT',
                'on'    => 'hotels.wardId = wards.id'
            ],

            // ===== PARTNER =====
            [
                'table' => 'partners',
                'type'  => 'LEFT',
                'on'    => 'hotels.partnerId = partners.userId'
            ],

            // ===== IMAGE =====
            [
                'table' => 'hotelimages',
                'type'  => 'LEFT',
                'on'    => 'hotels.id = hotelimages.hotelId AND hotelimages.isPrimary = 1'
            ]
        ],

        select: "
            hotels.*,

            cities.name AS cityName,
            wards.name AS wardName,

            partners.companyName,

            hotelimages.imageUrl AS image,

            /* ===== SUBQUERY THỐNG KÊ ===== */

            (
                SELECT COUNT(*)
                FROM roomconfigurations rc
                WHERE rc.hotelId = hotels.id
            ) AS totalRooms,

            (
                SELECT COUNT(*)
                FROM bookingdetails bd
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = hotels.id
            ) AS totalBookings,

            (
                SELECT COALESCE(SUM(b.totalAmount),0)
                FROM bookings b
                JOIN bookingdetails bd ON b.id = bd.bookingId
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = hotels.id
            ) AS totalRevenue,

            (
                SELECT COUNT(*)
                FROM reviews r
                JOIN bookingdetails bd ON r.bookingDetailId = bd.id
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = hotels.id
            ) AS totalReviews
        ",

        where: [
            'hotels.deletedAt IS NULL'
        ],

        orderBy: 'hotels.id DESC'
    );

    $this->view('layout/admin/admin', [
        'viewFile' => './app/views/admin/hotels.php',
        'hotels'   => $hotels,
        'partners' => $partners
    ]);
}






public function update_status_hotel()
{
    // ✅ Chỉ trả JSON
    header('Content-Type: application/json; charset=utf-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    // Lấy dữ liệu POST JSON
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input || empty($input['id']) || empty($input['status'])) {
        echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
        exit;
    }

    try {
        require_once './app/models/myModels.php';

        $hotelModel = new class extends myModels {
            protected $table = "hotels";
        };

        // Các trạng thái hợp lệ
        $validStatus = ['ACTIVE', 'STOP', 'PENDING_STOP'];
        if (!in_array($input['status'], $validStatus)) {
            echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
            exit;
        }

        // Update trạng thái
        $updateResult = $hotelModel->update(['status' => $input['status']], ['id' => $input['id']]);

        // Giả sử $updateResult trả về JSON kiểu {type: "success"} hoặc {type: "error"}
        $result = is_string($updateResult) ? json_decode($updateResult, true) : $updateResult;

        if ($result && in_array($result['type'] ?? '', ['success', 'warning'])) {
            echo json_encode([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công',
                'newStatus' => $input['status']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Không thể cập nhật trạng thái'
            ]);
        }
    } catch (\Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Lỗi hệ thống: ' . $e->getMessage()
        ]);
    }

    exit; // ✅ Không để HTML dư thừa
}
}