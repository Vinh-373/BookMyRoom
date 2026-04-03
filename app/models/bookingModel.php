<?php
namespace Models;
require_once "./app/models/MyModels.php";
class BookingModel extends MyModels {
    protected $table = "bookings";
     // ==================== CREATE BOOKING ====================
    public function createBooking($data)
    {
        $sql = "INSERT INTO bookings 
                (userId, status, totalAmount, platformFee, partnerRevenue, deposit) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Prepare lỗi: " . $this->conn->error);
        }

        $stmt->bind_param(
            "isdddd",
            $data['userId'],
            $data['status'],
            $data['totalAmount'],
            $data['platformFee'],
            $data['partnerRevenue'],
            $data['deposit']
        );

        if (!$stmt->execute()) {
            throw new \Exception("Insert booking lỗi: " . $stmt->error);
        }

        return $this->conn->insert_id;
    }

    // ==================== CREATE BOOKING DETAIL ====================
    public function createBookingDetail($data)
    {
        $sql = "INSERT INTO bookingdetails 
                (bookingId, roomConfigId, physicalRoomId, checkIn, checkOut, quantity, price, amount)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Prepare lỗi: " . $this->conn->error);
        }

        $stmt->bind_param(
            "iiissidd",
            $data['bookingId'],
            $data['roomConfigId'],
            $data['physicalRoomId'],
            $data['checkIn'],
            $data['checkOut'],
            $data['quantity'],
            $data['price'],
            $data['amount']
        );

        if (!$stmt->execute()) {
            throw new \Exception("Insert booking detail lỗi: " . $stmt->error);
        }

        return $this->conn->insert_id;
    }

    // ==================== LẤY PHÒNG TRỐNG (CHUẨN) ====================
    public function getAvailablePhysicalRooms($roomConfigId)
    {
        $sql = "SELECT id 
                FROM physicalrooms 
                WHERE roomConfigId = ? 
                AND status = 'AVAILABLE'
                FOR UPDATE"; // 🔥 khóa để tránh overbooking

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $roomConfigId);
        $stmt->execute();

        $result = $stmt->get_result();

        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row['id'];
        }

        return $rooms;
    }

    // ==================== TRỪ TRẠNG THÁI PHÒNG ====================
    public function markRoomsBooked($roomIds)
    {
        if (empty($roomIds)) return;

        $placeholders = implode(',', array_fill(0, count($roomIds), '?'));

        $sql = "UPDATE physicalrooms 
                SET status = 'BOOKED' 
                WHERE id IN ($placeholders)";

        $stmt = $this->conn->prepare($sql);

        $types = str_repeat('i', count($roomIds));
        $stmt->bind_param($types, ...$roomIds);

        if (!$stmt->execute()) {
            throw new \Exception("Update room lỗi: " . $stmt->error);
        }
    }
    function getRoomsByBooking($bookingId)
    {
        $sql = "SELECT physicalRoomId FROM bookingdetails WHERE bookingId=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $bookingId);
        $stmt->execute();
        $result = $stmt->get_result();

        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row['physicalRoomId'];
        }

        return $rooms;
    }
    function deleteBookingDetails($bookingId)
    {
        $sql = "DELETE FROM bookingdetails WHERE bookingId=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $bookingId);
        if (!$stmt->execute()) {
            throw new \Exception("Delete booking details lỗi: " . $stmt->error);
        }
    }

}