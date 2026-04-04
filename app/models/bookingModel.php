<?php
namespace Models;
require_once "./app/models/MyModels.php";
class BookingModel extends MyModels {
    protected $table = "bookings";
     // ==================== CREATE BOOKING ====================
    public function createBooking($data)
    {
        $sql = "INSERT INTO bookings 
                (userId, status, totalAmount, platformFee, partnerRevenue, deposit,voucherId) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Prepare lỗi: " . $this->conn->error);
        }

        $stmt->bind_param(
            "isdddds",
            $data['userId'],
            $data['status'],
            $data['totalAmount'],
            $data['platformFee'],
            $data['partnerRevenue'],
            $data['deposit'],
            $data['voucherId']
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
    
    /**
     * Lấy tổng platform_fee từ bảng bookings
     * Không dùng select_array hay executeQuery
     */
    public function getTotalPlatformFee(): float
    {
        $sql = "SELECT SUM(platformFee) AS total_platform_fee FROM {$this->table}";
        $result = $this->conn->query($sql); // mysqli query thuần

        if ($result) {
            $row = $result->fetch_assoc();
            return isset($row['total_platform_fee']) ? (float)$row['total_platform_fee'] : 0.0;
        }

        // Trả về 0 nếu query thất bại
        return 0.0;
    }
    public function countAllOrderActive(): int
    {
        $sql = "SELECT COUNT(*) AS total_active FROM {$this->table} WHERE status NOT IN ('COMPLETED', 'CANCELED')";
        $result = $this->conn->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            return isset($row['total_active']) ? (int)$row['total_active'] : 0;
        }

        return 0; // Nếu query thất bại
    }
    public function getMonthlyRevenue(int $year = null): array
    {
        $sql = "SELECT YEAR(createdAt) AS year, MONTH(createdAt) AS month, SUM(platformFee) AS revenue
            FROM {$this->table}";

        // Nếu có input năm, thêm điều kiện WHERE
        if ($year !== null) {
            $year = (int)$year; // bảo vệ input
            $sql .= " WHERE YEAR(createdAt) = $year";
        }

        $sql .= " GROUP BY YEAR(createdAt), MONTH(createdAt)
              ORDER BY YEAR(createdAt), MONTH(createdAt)";

        $result = $this->conn->query($sql);
        $monthlyRevenue = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $monthlyRevenue[] = [
                    'year' => (int)$row['year'],
                    'month' => (int)$row['month'],
                    'revenue' => (float)$row['revenue']
                ];
            }
        }

        return $monthlyRevenue;
    }
    public function get5LatestBookings(int $limit = 5): array
    {
        $sql = "SELECT *, users.id as userId,payments.createdAt as paymentCreatedAt FROM {$this->table} JOIN users ON {$this->table}.userId = users.id JOIN payments ON {$this->table}.id = payments.bookingId ORDER BY {$this->table}.createdAt DESC LIMIT $limit";
        $result = $this->conn->query($sql);
        $latestBookings = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $latestBookings[] = $row;
            }
        }

        return $latestBookings;
    }

}

