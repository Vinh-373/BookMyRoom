<?php
// require_once __DIR__ . '/../core/Model.php';

class HotelModel extends Model {

    // 1. Lấy danh sách khách sạn của Partner kèm số lượng phòng
    public function getHotelsByPartner($partnerId) {
        $sql = "SELECT 
                    h.*, 
                    c.name as cityName, 
                    w.name as wardName,
                    img.imageUrl as imageUrl,
                    -- Đếm tổng số phòng vật lý của khách sạn
                    (SELECT COUNT(*) FROM physicalRooms pr 
                    JOIN roomConfigurations rc ON pr.roomConfigId = rc.id 
                    WHERE rc.hotelId = h.id) as total_rooms,
                    -- Tính Rating trung bình bằng cách JOIN qua bookingDetails và roomConfigurations
                    (SELECT AVG(r.rating) 
                    FROM reviews r
                    JOIN bookingDetails bd ON r.bookingDetailId = bd.id
                    JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                    WHERE rc.hotelId = h.id) as rating
                FROM hotels h
                LEFT JOIN cities c ON h.cityId = c.id
                LEFT JOIN wards w ON h.wardId = w.id
                LEFT JOIN hotelimages img ON h.id = img.hotelId
                WHERE h.partnerId = :partnerId AND h.deletedAt IS NULL AND img.isPrimary = 1
                ORDER BY h.createdAt DESC";
        
        return $this->db->fetchAll($sql, [':partnerId' => $partnerId]);
    }

    // 2. Lấy tổng doanh thu chuỗi (MTD) - Dựa trên bảng bookings mới
    public function getChainTotalRevenue($partnerId) {
        $sql = "SELECT SUM(b.partnerRevenue) as total_revenue
                FROM bookings b
                JOIN bookingDetails bd ON b.id = bd.bookingId
                JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                JOIN hotels h ON rc.hotelId = h.id
                WHERE h.partnerId = :partnerId 
                AND MONTH(b.createdAt) = MONTH(CURRENT_DATE())
                AND YEAR(b.createdAt) = YEAR(CURRENT_DATE())";
        
        $result = $this->db->fetch($sql, [':partnerId' => $partnerId]);
        return $result['total_revenue'] ?? 0;
    }

    // 3. Lấy tổng số lượng booking của chuỗi trong tháng
    public function getChainTotalBookings($partnerId) {
        $sql = "SELECT COUNT(DISTINCT b.id) as total_bookings
                FROM bookings b
                JOIN bookingDetails bd ON b.id = bd.bookingId
                JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                JOIN hotels h ON rc.hotelId = h.id
                WHERE h.partnerId = :partnerId
                AND MONTH(b.createdAt) = MONTH(CURRENT_DATE())
                AND YEAR(b.createdAt) = YEAR(CURRENT_DATE())";
        
        $result = $this->db->fetch($sql, [':partnerId' => $partnerId]);
        return $result['total_bookings'] ?? 0;
    }

    // 4. Lấy chi tiết khách sạn kèm tên Thành phố/Phường
    public function getHotelById($hotelId) {
        $sql = "SELECT h.*, c.name as cityName, w.name as wardName 
                FROM hotels h
                LEFT JOIN cities c ON h.cityId = c.id
                LEFT JOIN wards w ON h.wardId = w.id
                WHERE h.id = :id LIMIT 1";
        
        return $this->db->fetch($sql, [':id' => $hotelId]);
    }

    // 5. Lấy doanh thu của 1 khách sạn cụ thể (MTD)
    public function getRevenueByHotel($hotelId) {
        $sql = "SELECT SUM(b.partnerRevenue) as total
                FROM bookings b
                JOIN bookingDetails bd ON b.id = bd.bookingId
                JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId 
                AND MONTH(b.createdAt) = MONTH(CURRENT_DATE())";
                
        $result = $this->db->fetch($sql, [':hotelId' => $hotelId]);
        return $result['total'] ?? 0;
    }

    // 6. Lấy trạng thái các phòng vật lý cho Dashboard
    public function getPhysicalRoomsStatus($hotelId) {
        $sql = "SELECT pr.*, rc.basePrice as price, rt.name as typeName
                FROM physicalRooms pr
                JOIN roomConfigurations rc ON pr.roomConfigId = rc.id
                JOIN roomTypes rt ON rc.roomTypeId = rt.id
                WHERE rc.hotelId = :hotelId";
                
        return $this->db->fetchAll($sql, [':hotelId' => $hotelId]);
    }

    // 7. Lấy dữ liệu công suất phòng (Occupancy)
    public function getOccupancyData($hotelId) {
        $sql = "SELECT 
                SUM(CASE WHEN pr.status = 'OCCUPIED' THEN 1 ELSE 0 END) as occupied_count,
                COUNT(pr.id) as total_count
                FROM physicalRooms pr
                JOIN roomConfigurations rc ON pr.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId";
        
        return $this->db->fetch($sql, [':hotelId' => $hotelId]);
    }

    // 8. Thống kê khách đến hôm nay
    public function getTodayArrivals($hotelId) {
        $sql = "SELECT COUNT(*) as total FROM bookingDetails bd
                JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId AND DATE(bd.checkIn) = CURRENT_DATE()";
        $result = $this->db->fetch($sql, [':hotelId' => $hotelId]);
        return $result['total'] ?? 0;
    }

    // 9. Thống kê khách đi hôm nay
    public function getTodayDepartures($hotelId) {
        $sql = "SELECT COUNT(*) as total FROM bookingDetails bd
                JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId AND DATE(bd.checkOut) = CURRENT_DATE()";
        $result = $this->db->fetch($sql, [':hotelId' => $hotelId]);
        return $result['total'] ?? 0;
    }

    // 10. Lấy 7 hoạt động đặt phòng gần đây nhất (Recent Activity)
    public function getRecentActivity($hotelId) {
        $sql = "SELECT u.fullName as guestName, rt.name as roomType, 
                       bd.checkIn, bd.checkOut, b.totalAmount as amount, 
                       b.status, p.paymentStatus
                FROM bookings b
                JOIN bookingDetails bd ON b.id = bd.bookingId
                JOIN users u ON b.userId = u.id
                JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                JOIN roomTypes rt ON rc.roomTypeId = rt.id
                LEFT JOIN payments p ON b.id = p.bookingId
                WHERE rc.hotelId = :hotelId
                ORDER BY b.createdAt DESC LIMIT 7";
        return $this->db->fetchAll($sql, [':hotelId' => $hotelId]);
    }

    // 11. Biểu đồ doanh thu 30 ngày qua
    public function getDailyRevenueLast30Days($hotelId) {
        $sql = "SELECT DATE(b.createdAt) as date, SUM(b.partnerRevenue) as daily_revenue
                FROM bookings b
                JOIN bookingDetails bd ON b.id = bd.bookingId
                JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId 
                AND b.createdAt >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
                GROUP BY DATE(b.createdAt)
                ORDER BY DATE(b.createdAt) ASC";
                
        return $this->db->fetchAll($sql, [':hotelId' => $hotelId]);
    }

    public function getBookingSources($hotelId) {
        $sql = "SELECT b.source, SUM(b.partnerRevenue) as revenue
                FROM bookings b
                JOIN bookingDetails bd ON b.id = bd.bookingId
                JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId
                GROUP BY b.source";
        
        $results = $this->db->fetchAll($sql, [':hotelId' => $hotelId]);
        $total = array_sum(array_column($results, 'revenue'));
        
        return array_map(function($row) use ($total) {
            return [
                'source' => $row['source'],
                'percentage' => ($total > 0) ? round(($row['revenue'] / $total) * 100) : 0
            ];
        }, $results);
    }

    // Thêm khách sạn
    public function insert($data) {
        $sql = "INSERT INTO hotels (partnerId, hotelName, description, cityId, wardId, address, rating, createdAt) 
                VALUES (:pId, :name, :desc, :cId, :wId, :addr, 0, NOW())";
        
        return $this->db->query($sql, [
            ':pId'   => $data['partnerId'],
            ':name'  => $data['hotelName'],
            ':desc'  => $data['description'],
            ':cId'   => $data['cityId'],
            ':wId'   => $data['wardId'],
            ':addr'  => $data['address']
        ]);
    }

    public function getCities() {
        return $this->db->fetchAll("SELECT * FROM cities ORDER BY name ASC");
    }

    public function getWardsByCity($cityId) {
        return $this->db->fetchAll("SELECT * FROM wards WHERE cityId = :cId", [':cId' => $cityId]);
    }

    public function updateStatus($hotelId, $status) {
        $sql = "UPDATE hotels SET status = :status WHERE id = :id";
        return $this->db->query($sql, [':status' => $status, ':id' => $hotelId]);
    }

    // Phê duyệt dừng hoạt động (Soft Delete)
    public function approveStop($hotelId) {
        $sql = "UPDATE hotels SET status = 'STOPPED', deletedAt = NOW() WHERE id = :id";
        return $this->db->query($sql, [':id' => $hotelId]);
    }

    // Kiểm tra xem khách sạn có đơn hàng nào chưa hoàn tất không
    public function hasActiveBookings($hotelId) {
        $sql = "SELECT COUNT(*) as count FROM bookings b
                JOIN bookingdetails bd ON b.id = bd.bookingId
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hId AND b.status IN ('PENDING', 'CONFIRMED')";
        $result = $this->db->fetch($sql, [':hId' => $hotelId]);
        return $result['count'] > 0;
    }

    public function getById($id) {
        $sql = "SELECT * FROM hotels WHERE id = :id AND deletedAt IS NULL";
        return $this->db->fetch($sql, [':id' => $id]);
    }

    public function update($id, $data) {
        $sql = "UPDATE hotels 
                SET hotelName = :name, 
                    description = :desc, 
                    cityId = :cId, 
                    wardId = :wId, 
                    address = :addr 
                WHERE id = :id";
                
        return $this->db->query($sql, [
            ':name' => $data['hotelName'],
            ':desc' => $data['description'],
            ':cId'  => $data['cityId'],
            ':wId'  => $data['wardId'],
            ':addr' => $data['address'],
            ':id'   => $id
        ]);
    }
}