<?php

namespace Controllers\admin;

use Controller;
use Models\MyModels;
require_once "./app/models/myModels.php";
use Models\paymentsModel;
require_once "./app/models/paymentsModel.php";
class Statisticals extends Controller
{
    public function index()
    {
       if (empty($_SESSION["admin_id"]) || empty($_SESSION["admin_name"])) {
    // Chuyển hướng về trang auth (đăng nhập)
    header("Location: /BookMyRoom/admin/auth");
    exit(); // Luôn phải có exit để dừng thực thi code phía dưới
}

        $paymentsModel = new paymentsModel();

        // ===== PAYMENTS JOIN =====
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
                users.role, -- ✅ dùng trực tiếp role ở đây

                hotels.hotelName,
                hotels.address,

                partners.companyName
            ",
            where: [],
            orderBy: 'payments.id DESC'
        );

        // ===== USERS =====
        $usersModel = new class extends myModels {
            protected $table = "users";
        };
        $users = $usersModel->findAll();

        // ===== HOTELS =====
        $hotelsModel = new class extends myModels {
            protected $table = "hotels";
        };
        $hotels = $hotelsModel->findAll();

        // ===== ROOM CONFIG =====
        $roomConfigModel = new class extends myModels {
            protected $table = "roomconfigurations";
        };
        $roomconfigurations = $roomConfigModel->findAll();

        // ===== PHYSICAL ROOMS =====
        $physicalRoomsModel = new class extends myModels {
            protected $table = "physicalrooms";
        };
        $physicalrooms = $physicalRoomsModel->findAll();

        // ===== BOOKINGS =====
        $bookingsModel = new class extends myModels {
            protected $table = "bookings";
        };
        $bookings = $bookingsModel->findAll();

        // ===== BOOKING DETAILS =====
        $bookingdetailsModel = new class extends myModels {
            protected $table = "bookingdetails";
        };
        $bookingdetails = $bookingdetailsModel->findAll();

        // ===== REVIEWS =====
        $reviewsModel = new class extends myModels {
            protected $table = "reviews";
        };
        $reviews = $reviewsModel->findAll();

        // ===== VIEW =====
        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/statisticals.php',

            'payments' => $payments,
            'users' => $users,
            'hotels' => $hotels,
            'roomconfigurations' => $roomconfigurations,
            'physicalrooms' => $physicalrooms,
            'bookings' => $bookings,
            'reviews' => $reviews,
            'bookingdetails' => $bookingdetails
        ]);
    }
}