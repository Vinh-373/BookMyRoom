<?php
require_once __DIR__ . '/../core/Model.php';

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
                WHERE h.partnerId = :partnerId AND h.deletedAt IS NULL
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
}