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
}
