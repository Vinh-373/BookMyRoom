<?php

namespace Controllers\admin;

use Controller;

class Payments extends Controller
{
    public function index()
    {
        require_once './app/models/myModels.php';
        require_once './app/models/paymentsModel.php';
        $paymentsModel = new \paymentsModel();
        $payments = $paymentsModel->join_multi(
            joins: [
                [
                    'table' => 'bookings',
                    'type'  => 'LEFT',
                    'on'    => 'payments.bookingId = bookings.id'
                ],
                [
                    'table' => 'users',
                    'type'  => 'LEFT',
                    'on'    => 'bookings.userId = users.id'
                ],
                [
                    'table' => 'bookingdetails',
                    'type'  => 'LEFT',
                    'on'    => 'bookingdetails.bookingId = bookings.id'
                ],
                [
                    'table' => 'roomconfigurations',
                    'type'  => 'LEFT',
                    'on'    => 'bookingdetails.roomConfigId = roomconfigurations.id'
                ],
                [
                    'table' => 'hotels',
                    'type'  => 'LEFT',
                    'on'    => 'roomconfigurations.hotelId = hotels.id'
                ],
                [
                    'table' => 'partners',
                    'type'  => 'LEFT',
                    'on'    => 'hotels.partnerId = partners.userId'
                ]
            ],
            select: "
                payments.*,

                bookings.status AS bookingStatus,
                bookings.totalAmount,
                bookings.platformFee,
                bookings.partnerRevenue,

                users.fullName,
                users.email,
                users.phone,

                hotels.hotelName,
                hotels.address,

                partners.companyName
            ",
            where: [],
            orderBy: 'payments.id DESC'
        );

        // truyền view
        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/payments.php',
            'payments' => $payments
        ]);
    }

public function update_status_payment()
{
    header('Content-Type: application/json; charset=utf-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input || !isset($input['id']) || !isset($input['status'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Thiếu dữ liệu'
        ]);
        exit;
    }

    try {
        require_once './app/models/myModels.php';

        $paymentModel = new class extends \myModels {
            protected $table = "payments";
        };

        $validStatus = ['PENDING', 'PAID', 'FAILED', 'REFUNDED'];

        if (!in_array($input['status'], $validStatus)) {
            echo json_encode([
                'success' => false,
                'message' => 'Trạng thái không hợp lệ'
            ]);
            exit;
        }

        // 🔥 update giống partners
        $result = json_decode(
            $paymentModel->update(
                ['paymentStatus' => $input['status']],
                ['id' => $input['id']]
            ),
            true
        );

        if ($result && in_array($result['type'], ['success', 'warning'])) {
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

    exit;
}


}
