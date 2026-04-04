<?php

namespace Controllers\admin;

use Controller;

class Hotels extends Controller
{
   public function index()
{
    require_once './app/models/myModels.php';
    require_once './app/models/hotelsModel.php';
    require_once './app/models/partnersModel.php';

    $partnersModel = new \partnersModel();
    $partners = $partnersModel->findAll();

    $hotelsModel = new \hotelsModel();

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
}