<?php

namespace Controllers\admin;

use Controller;

class Bookings extends Controller
{
    public function index()
    {
        require_once './app/models/myModels.php';
        require_once './app/models/bookingsModel.php';

        $bookingsModel = new \bookingsModel();

        $bookings = $bookingsModel->join_multi(
            joins: [
                [
                    'table' => 'users',
                    'type'  => 'LEFT',
                    'on'    => 'bookings.userId = users.id'
                ],
                [
                    'table' => 'payments',
                    'type'  => 'LEFT',
                    'on'    => 'payments.bookingId = bookings.id'
                ]
            ],

            select: "
                bookings.id,
                bookings.status,
                bookings.totalAmount,
                bookings.createdAt,

                users.fullName,

                -- 🔥 Lấy check-in sớm nhất
                (
                    SELECT MIN(checkIn)
                    FROM bookingdetails
                    WHERE bookingdetails.bookingId = bookings.id
                ) AS checkIn,

                -- 🔥 Lấy check-out muộn nhất
                (
                    SELECT MAX(checkOut)
                    FROM bookingdetails
                    WHERE bookingdetails.bookingId = bookings.id
                ) AS checkOut,

                payments.paymentStatus
            ",

            where: [],

            orderBy: 'bookings.id DESC'
        );

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/bookings.php',
            'bookings' => $bookings
        ]);
    }
    public function update_status()
{
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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

    $validStatus = ['PENDING', 'CONFIRMED', 'COMPLETED', 'CANCELLED'];

    if (!in_array($input['status'], $validStatus)) {
        echo json_encode([
            'success' => false,
            'message' => 'Status không hợp lệ'
        ]);
        exit;
    }

    require_once './app/models/myModels.php';

    $model = new class extends \myModels {
        protected $table = "bookings";
    };

    $result = json_decode(
        $model->update(
            ['status' => $input['status']],
            ['id' => (int)$input['id']]
        ),
        true
    );

    echo json_encode([
        'success' => in_array($result['type'], ['success', 'warning']),
        'debug' => $result // 👉 debug nếu cần
    ]);

    exit;
}
}