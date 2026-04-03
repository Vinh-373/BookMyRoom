<?php

namespace Services;

use Models\BookingModel;

require_once "./app/models/bookingModel.php";
class BookingService
{
    private $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
    }
    function createBooking($data)
    {
        return $this->bookingModel->createBooking($data);
    }
    function createBookingDetail($data)
    {
        return $this->bookingModel->createBookingDetail($data);
    }
    function getAvailablePhysicalRooms($roomConfigId)
    {
        return $this->bookingModel->getAvailablePhysicalRooms($roomConfigId);
    }
    function markRoomsBooked($roomIds)
    {
        return $this->bookingModel->markRoomsBooked($roomIds);
    }
    function updateBooking($bookingId, $data)
    {
        return $this->bookingModel->update($data, ['id' => $bookingId]);
    }

    public function getRoomsByBooking($bookingId)
    {
        return $this->bookingModel->getRoomsByBooking($bookingId);
    }
    public function markRoomsAvailable($roomId)
    {
        return $this->bookingModel->update(['status' => 'AVAILABLE'], ['id' => $roomId]);
    }
    public function deleteBookingDetails($bookingId)
    {
        return $this->bookingModel->deleteBookingDetails($bookingId);
    }
}
