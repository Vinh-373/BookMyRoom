<?php

namespace Controllers\admin;

use Controller;

class Statisticals extends Controller
{
    public function index()
    {
        require_once './app/models/myModels.php';
        require_once './app/models/paymentsModel.php';

        $paymentsModel = new \paymentsModel();

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

                hotels.hotelName,
                hotels.address,

                partners.companyName
            ",
            where: [],
            orderBy: 'payments.id DESC'
        );

        // ===== USERS =====
        $usersModel = new class extends \myModels {
            protected $table = "users";
        };
        $users = $usersModel->findAll();

        // ===== USER ROLES =====
        $userRolesModel = new class extends \myModels {
            protected $table = "user_roles";
        };
        $user_roles = $userRolesModel->findAll();

        // ===== HOTELS =====
        $hotelsModel = new class extends \myModels {
            protected $table = "hotels";
        };
        $hotels = $hotelsModel->findAll();

        // ===== ROOM CONFIG =====
        $roomConfigModel = new class extends \myModels {
            protected $table = "roomconfigurations";
        };
        $roomconfigurations = $roomConfigModel->findAll();

        // ===== PHYSICAL ROOMS =====
        $physicalRoomsModel = new class extends \myModels {
            protected $table = "physicalrooms";
        };
        $physicalrooms = $physicalRoomsModel->findAll();

        // ===== BOOKINGS =====
        $bookingsModel = new class extends \myModels {
            protected $table = "bookings";
        };
        $bookings = $bookingsModel->findAll();
                // ===== BOOKING DETAILS (THÊM MỚI) =====
        $bookingdetails = (new class extends \myModels {
            protected $table = "bookingdetails";
        })->findAll();
        // ===== REVIEWS (THÊM MỚI) =====
        $reviews = (new class extends \myModels {
            protected $table = "reviews";
        })->findAll();
        // ===== VIEW =====
        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/statisticals.php',

            'payments' => $payments,
            'users' => $users,
            'user_roles' => $user_roles,
            'hotels' => $hotels,
            'roomconfigurations' => $roomconfigurations,
            'physicalrooms' => $physicalrooms,
            'bookings' => $bookings,
            'reviews' => $reviews,
            'bookingdetails'=> $bookingdetails
        ]);
    }
}
