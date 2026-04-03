<?php

namespace Controllers\admin;

use Controller;

class Reviews extends Controller
{
    public function index()
    {
        require_once './app/models/myModels.php';
        require_once './app/models/reviewsModel.php';
        require_once './app/models/partnersModel.php';
        $partnersModel = new \partnersModel();
        $partners = $partnersModel->findAll();


        $reviewsModel = new \reviewsModel();

        $reviews = $reviewsModel->join_multi(
            joins: [
                [
                    'table' => 'users',
                    'type'  => 'LEFT',
                    'on'    => 'reviews.userId = users.id'
                ],
                [
                    'table' => 'bookingdetails',
                    'type'  => 'LEFT',
                    'on'    => 'reviews.bookingDetailId = bookingdetails.id'
                ],
                [
                    'table' => 'bookings',
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
                reviews.*,

                users.fullName,
                users.email,
                users.phone,

                bookingdetails.id AS bookingId,
                bookings.status AS bookingStatus,
                bookings.totalAmount,

                hotels.hotelName,
                hotels.address,
                partners.companyName,
                partners.userId AS partnerId
            ",
            where: [],
            orderBy: 'reviews.id DESC'
        );

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/reviews.php',
            'reviews' => $reviews,
            'partners' => $partners
        ]);
    }


    public function delete_review()
    {
        require_once './app/models/myModels.php';

        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;

        if (!$id) {
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu ID'
            ]);
            exit;
        }

        $reviewsModel = new class extends \myModels {
            protected $table = "reviews";
        };

        $conn = $reviewsModel->conn;

        $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();

        echo json_encode([
            'success' => $ok
        ]);
        exit;
    }
}
