<?php

namespace Controllers\admin;

use Controller;
use Models\myModels;
use Models\BookingsModel;
require_once "./app/models/bookingsModel.php";
class Bookings extends Controller
{

public function index()
{
    require_once './app/models/myModels.php';

    $bookingsModel = new class extends myModels {
        protected $table = "bookings";
    };

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

            (
                SELECT MIN(checkIn)
                FROM bookingdetails
                WHERE bookingdetails.bookingId = bookings.id
            ) AS checkIn,

            (
                SELECT MAX(checkOut)
                FROM bookingdetails
                WHERE bookingdetails.bookingId = bookings.id
            ) AS checkOut,

            payments.paymentStatus,

            (
                SELECT hotels.hotelName
                FROM bookingdetails
                JOIN roomconfigurations 
                    ON bookingdetails.roomConfigId = roomconfigurations.id
                JOIN hotels 
                    ON roomconfigurations.hotelId = hotels.id
                WHERE bookingdetails.bookingId = bookings.id
                LIMIT 1
            ) AS hotelName,

            (
                SELECT roomtypes.name
                FROM bookingdetails
                JOIN roomconfigurations 
                    ON bookingdetails.roomConfigId = roomconfigurations.id
                JOIN roomtypes 
                    ON roomconfigurations.roomTypeId = roomtypes.id
                WHERE bookingdetails.bookingId = bookings.id
                LIMIT 1
            ) AS roomName
        ",

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

$input = json_decode(file_get_contents("php://input"), true);

if(!$input){
echo json_encode(['success'=>false]);
exit;
}

require_once './app/models/myModels.php';

$model = new class extends myModels {
protected $table = "bookings";
};

$result = json_decode(
$model->update(
['status'=>$input['status']],
['id'=>(int)$input['id']]
),
true
);

echo json_encode([
'success'=> in_array($result['type'],['success','warning'])
]);

exit;
}

}