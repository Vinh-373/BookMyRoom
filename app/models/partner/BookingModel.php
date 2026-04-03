<?php
// require_once __DIR__ . '/../core/Model.php';

class BookingModel extends Model {
    public function getBookings($hotelId, $filters = [], $limit = 10, $offset = 0) {
        $params = [':hotelId' => $hotelId];
        $where = "WHERE rc.hotelId = :hotelId";

        // 1. Lọc theo Tab (Trạng thái đặt phòng)
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            if ($filters['status'] === 'upcoming') {
                // Khách chưa tới: Trạng thái chưa hủy và ngày Check-in từ hôm nay trở về sau
                $where .= " AND b.status IN ('PENDING', 'CONFIRMED') AND bd.checkIn >= CURDATE()";
            } elseif ($filters['status'] === 'staying') {
                // Khách đang ở: Đã xác nhận và hôm nay nằm trong khoảng ở
                $where .= " AND b.status = 'CONFIRMED' AND CURDATE() BETWEEN bd.checkIn AND bd.checkOut";
            } elseif ($filters['status'] === 'completed') {
                $where .= " AND b.status = 'COMPLETED'";
            } elseif ($filters['status'] === 'cancelled') {
                $where .= " AND b.status = 'CANCELLED'";
            } else {
                $where .= " AND b.status = :status";
                $params[':status'] = strtoupper($filters['status']);
            }
        }

        // 2. Lọc theo Loại phòng
        if (!empty($filters['roomTypeId'])) {
            $where .= " AND rt.id = :roomTypeId";
            $params[':roomTypeId'] = $filters['roomTypeId'];
        }

        // 3. Tìm kiếm (ID, Tên khách, Số điện thoại)
        if (!empty($filters['search'])) {
            $where .= " AND (b.id LIKE :search OR u.fullName LIKE :search OR u.phone LIKE :search)";
            $params[':search'] = "%" . $filters['search'] . "%";
        }

        // 4. Lọc theo khoảng ngày (Sửa lỗi lặp và gộp lại)
        if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
            $where .= " AND bd.checkIn BETWEEN :start AND :end";
            $params[':start'] = $filters['startDate'];
            $params[':end'] = $filters['endDate'];
        }

        // 5. Xử lý Sắp xếp
        $orderBy = "ORDER BY b.createdAt DESC"; 
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'oldest': $orderBy = "ORDER BY b.createdAt ASC"; break;
                case 'price_high': $orderBy = "ORDER BY b.totalAmount DESC"; break;
                case 'price_low': $orderBy = "ORDER BY b.totalAmount ASC"; break;
                case 'checkin_near': $orderBy = "ORDER BY bd.checkIn ASC"; break;
            }
        }

        // Dùng INNER JOIN để đảm bảo chỉ lấy các booking có đầy đủ thông tin chi tiết
        $sql = "SELECT 
                    b.id, 
                    b.createdAt, 
                    b.status as bookingStatus, 
                    b.totalAmount, 
                    u.fullName, 
                    u.phone, 
                    u.avatarUrl,
                    rt.name as roomTypeName,
                    bd.checkIn,
                    bd.checkOut,
                    DATEDIFF(bd.checkOut, bd.checkIn) as nights,
                    p.paymentStatus
                FROM bookings b
                INNER JOIN users u ON b.userId = u.id
                INNER JOIN bookingDetails bd ON b.id = bd.bookingId
                INNER JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                INNER JOIN roomTypes rt ON rc.roomTypeId = rt.id
                LEFT JOIN payments p ON b.id = p.bookingId
                $where
                $orderBy
                LIMIT $limit OFFSET $offset";

        return $this->db->fetchAll($sql, $params);
    }

    public function getTotalBookingCount($hotelId, $filters = []) {
        $params = [':hotelId' => $hotelId];
        $where = "WHERE rc.hotelId = :hotelId";

        // Copy toàn bộ logic WHERE từ hàm getBookings sang đây (trừ phần ORDER BY và LIMIT)
        if (!empty($filters['status']) && $filters['status'] !== 'all') { /* ... logic status ... */ }
        if (!empty($filters['roomTypeId'])) { /* ... logic roomType ... */ }
        if (!empty($filters['search'])) { /* ... logic search ... */ }

        $sql = "SELECT COUNT(DISTINCT b.id) as total 
                FROM bookings b
                JOIN bookingDetails bd ON b.id = bd.bookingId
                JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                JOIN roomTypes rt ON rc.roomTypeId = rt.id
                $where";

        $result = $this->db->fetch($sql, $params);
        return $result['total'] ?? 0;
    }

    public function getRoomTypesByHotel($hotelId) {
        $sql = "SELECT DISTINCT rt.id, rt.name 
                FROM roomTypes rt
                JOIN roomConfigurations rc ON rt.id = rc.roomTypeId
                WHERE rc.hotelId = :hotelId";
                
        return $this->db->fetchAll($sql, [':hotelId' => $hotelId]);
    }

    // Lấy doanh thu tháng này (MTD)
    public function getMonthlyRevenue($hotelId) {
        $sql = "SELECT SUM(partnerRevenue) as total 
                FROM bookings b
                JOIN bookingDetails bd ON b.id = bd.bookingId
                JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId 
                AND MONTH(b.createdAt) = MONTH(CURRENT_DATE())
                AND YEAR(b.createdAt) = YEAR(CURRENT_DATE())";
        $result = $this->db->fetch($sql, [':hotelId' => $hotelId]);
        return $result['total'] ?? 0;
    }

    // Lấy dữ liệu công suất phòng thực tế (Occupancy)
    public function getRealtimeOccupancy($hotelId) {
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM physicalRooms pr 
                    JOIN roomConfigurations rc ON pr.roomConfigId = rc.id 
                    WHERE rc.hotelId = :hotelId AND pr.status = 'OCCUPIED') as occupied,
                    (SELECT COUNT(*) FROM physicalRooms pr 
                    JOIN roomConfigurations rc ON pr.roomConfigId = rc.id 
                    WHERE rc.hotelId = :hotelId) as total";
        return $this->db->fetch($sql, [':hotelId' => $hotelId]);
    }

    public function getTotalRevenueByHotel($hotelId, $start, $end) {
        $sql = "SELECT SUM(totalAmount) as total 
                FROM bookings b
                JOIN bookingdetails bd ON b.id = bd.bookingId
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId 
                AND b.status = 'COMPLETED'
                AND b.createdAt BETWEEN :start AND :end";
        
        $result = $this->db->fetch($sql, [
            ':hotelId' => $hotelId,
            ':start' => $start,
            ':end' => $end
        ]);
        return $result['total'] ?? 0;
    }

    // Lấy doanh thu phân loại theo Loại phòng (Dùng cho biểu đồ)
    public function getRevenueGroupedByRoomType($hotelId, $start, $end) {
        $sql = "SELECT rt.name as room_type, SUM(b.totalAmount) as amount
                FROM bookings b
                JOIN bookingdetails bd ON b.id = bd.bookingId
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                JOIN roomtypes rt ON rc.roomTypeId = rt.id
                WHERE rc.hotelId = :hotelId
                AND b.status = 'COMPLETED'
                AND b.createdAt BETWEEN :start AND :end
                GROUP BY rt.id";
        
        return $this->db->fetchAll($sql, [
            ':hotelId' => $hotelId,
            ':start' => $start,
            ':end' => $end
        ]);
    }

    // Lấy danh sách giao dịch gần đây
    public function getRecentTransactions($hotelId, $limit) {
        $limit = (int)$limit; 
        $sql = "SELECT b.id as transaction_id, b.totalAmount as amount, b.status, b.createdAt as created_at
                FROM bookings b
                JOIN bookingdetails bd ON b.id = bd.bookingId
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId
                ORDER BY b.createdAt DESC
                LIMIT $limit";
        
        return $this->db->fetchAll($sql, [
            ':hotelId' => $hotelId
        ]);
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE bookings SET status = :status WHERE id = :id"; 
        return $this->db->query($sql, [
            ':status' => $status,
            ':id' => $id
        ]);
    }

    /**
     * Cập nhật thời điểm Check-in thực tế
     */
    public function updateActualCheckIn($id) {
        $sql = "UPDATE bookings SET actualCheckIn = NOW() WHERE id = :id";
        return $this->db->query($sql, [':id' => $id]);
    }

    /**
     * Cập nhật thời điểm Check-out thực tế
     */
    public function updateActualCheckOut($id) {
        $sql = "UPDATE bookings SET actualCheckOut = NOW() WHERE id = :id";
        return $this->db->query($sql, [':id' => $id]);
    }
}