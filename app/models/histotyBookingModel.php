<?php

namespace Models;
require_once "./app/models/MyModels.php";

class HistoryBookingModel extends MyModels
{
    protected $table = "bookings";

    public function getHistoryByUser($userId)
    {
        $sql = "
        SELECT
            b.id ,
            b.createdAt ,
            b.status,
            b.totalAmount ,
            b.deposit,
            b.userId,
            u.fullName

        FROM bookings b
        LEFT JOIN users u ON b.userId = u.id
        WHERE b.userId = ?
        ORDER BY b.createdAt DESC
    ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        // bind tham số (i = integer)
        $stmt->bind_param("i", $userId);

        // execute (KHÔNG truyền tham số)
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        // lấy dữ liệu
        $result = $stmt->get_result();

        // fetch tất cả
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function getBookingDetails($bookingId)
    {
        $sql = "
        SELECT
            bd.id,
            bd.bookingId,
            bd.roomConfigId,
            bd.physicalRoomId,
            bd.checkIn,
            bd.checkOut,
            bd.quantity,
            bd.price,
            bd.amount,

            rcf.hotelId,
            rcf.roomTypeId,
            rcf.area,

            h.hotelName,

            pr.roomNumber,
            pr.floor,

            rt.name AS roomTypeName,

            r.id as reviewId

        FROM bookingdetails bd
        LEFT JOIN roomconfigurations rcf ON bd.roomConfigId = rcf.id
        LEFT JOIN hotels h ON rcf.hotelId = h.id
        LEFT JOIN physicalrooms pr ON bd.physicalRoomId = pr.id
        LEFT JOIN roomtypes rt ON rcf.roomTypeId = rt.id
        LEFT JOIN reviews r ON bd.id = r.bookingDetailId

        WHERE bd.bookingId = ?
        ";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $bookingId); // "i" = integer

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }




    public function getHotelName($hotelId)
    {
        $sql = "SELECT h.hotelName FROM hotels h WHERE h.id = ?";
        $stmt = $this->conn->prepare($sql);

        // bind tham số (i = integer)
        $stmt->bind_param("i", $hotelId);

        // execute KHÔNG truyền tham số
        $stmt->execute();

        // lấy kết quả
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        return $data;
    }

    public function setReview($userId,$bookingDetailId, $rating, $content,$hotelId)
    {
        $sql = "INSERT INTO reviews (userId, bookingDetailId, rating, content, hotelId) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiisi", $userId, $bookingDetailId, $rating, $content,  $hotelId);
        return $stmt->execute();
    }
}