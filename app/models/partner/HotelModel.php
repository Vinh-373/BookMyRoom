<?php

class HotelModel extends Model {

    /**
     * 1. Lấy danh sách khách sạn của Partner kèm ảnh chính, số lượng phòng và rating
     * Tích hợp thêm mảng 'images' cho mỗi khách sạn để dùng cho Modal Edit
     */
    public function getHotelsByPartner($partnerId) {
        $sql = "SELECT 
                    h.*, 
                    c.name as cityName, 
                    w.name as wardName,
                    -- Lấy URL của ảnh được đánh dấu là ảnh chính (isPrimary = 1)
                    (SELECT imageUrl FROM hotelimages WHERE hotelId = h.id AND isPrimary = 1 LIMIT 1) as imageUrl,
                    -- Đếm tổng số phòng vật lý của khách sạn
                    (SELECT COUNT(*) FROM physicalRooms pr 
                     JOIN roomConfigurations rc ON pr.roomConfigId = rc.id 
                     WHERE rc.hotelId = h.id) as total_rooms,
                    -- Tính Rating trung bình
                    (SELECT AVG(r.rating) 
                     FROM reviews r
                     JOIN bookingDetails bd ON r.bookingDetailId = bd.id
                     JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                     WHERE rc.hotelId = h.id) as rating
                FROM hotels h
                LEFT JOIN cities c ON h.cityId = c.id
                LEFT JOIN wards w ON h.wardId = w.id
                WHERE h.partnerId = :partnerId AND h.deletedAt IS NULL
                ORDER BY h.createdAt DESC";
        
        $hotels = $this->db->fetchAll($sql, [':partnerId' => $partnerId]);

        // Đổ thêm danh sách toàn bộ URL ảnh vào từng khách sạn để JS Modal Edit sử dụng
        foreach ($hotels as &$hotel) {
            $hotel['images'] = $this->getImagesByHotel($hotel['id']);
        }

        return $hotels;
    }

    /**
     * 2. Quản lý Hình ảnh (hotelimages)
     */
    public function addHotelImage($hotelId, $url, $isPrimary) {
        $sql = "INSERT INTO hotelimages (hotelId, imageUrl, isPrimary) VALUES (:hId, :url, :isP)";
        return $this->db->query($sql, [
            ':hId'  => $hotelId,
            ':url'  => $url,
            ':isP'  => $isPrimary
        ]);
    }

    public function deleteImagesByHotel($hotelId) {
        $sql = "DELETE FROM hotelimages WHERE hotelId = :hId";
        return $this->db->query($sql, [':hId' => $hotelId]);
    }

    public function getImagesByHotel($hotelId) {
        $sql = "SELECT imageUrl as url, isPrimary FROM hotelimages WHERE hotelId = :hId";
        return $this->db->fetchAll($sql, [':hId' => $hotelId]);
    }

    /**
     * 3. Thao tác Cơ bản (CRUD)
     */
    public function insert($data) {
        $sql = "INSERT INTO hotels (partnerId, hotelName, description, cityId, wardId, address, rating, status, createdAt) 
                VALUES (:pId, :name, :desc, :cId, :wId, :addr, 0, 'ACTIVE', NOW())";
        
        $result = $this->db->query($sql, [
            ':pId'   => $data['partnerId'],
            ':name'  => $data['hotelName'],
            ':desc'  => $data['description'],
            ':cId'   => $data['cityId'],
            ':wId'   => $data['wardId'],
            ':addr'  => $data['address']
        ]);

        // Trả về ID vừa chèn để Service lưu ảnh
        return $result ? $this->db->lastInsertId() : false;
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

    public function getById($id) {
        $sql = "SELECT h.*, c.name as cityName, w.name as wardName 
                FROM hotels h
                LEFT JOIN cities c ON h.cityId = c.id
                LEFT JOIN wards w ON h.wardId = w.id
                WHERE h.id = :id AND h.deletedAt IS NULL LIMIT 1";
        return $this->db->fetch($sql, [':id' => $id]);
    }

    /**
     * 4. Thống kê Doanh thu & Booking (Chain Dashboard)
     */
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

    public function getChainTotalBookings($partnerId) {
        $sql = "SELECT COUNT(DISTINCT b.id) as total_bookings
                FROM bookings b
                JOIN bookingDetails bd ON b.id = bd.bookingId
                JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                JOIN hotels h ON rc.hotelId = h.id
                WHERE h.partnerId = :partnerId
                AND MONTH(b.createdAt) = MONTH(CURRENT_DATE())";
        
        $result = $this->db->fetch($sql, [':partnerId' => $partnerId]);
        return $result['total_bookings'] ?? 0;
    }

    /**
     * 5. Quản lý Trạng thái & Dừng hoạt động
     */
    public function updateStatus($hotelId, $status) {
        $sql = "UPDATE hotels SET status = :status WHERE id = :id";
        return $this->db->query($sql, [':status' => $status, ':id' => $hotelId]);
    }

    public function approveStop($hotelId) {
        $sql = "UPDATE hotels SET status = 'STOPPED', deletedAt = NOW() WHERE id = :id";
        return $this->db->query($sql, [':id' => $hotelId]);
    }

    public function hasActiveBookings($hotelId) {
        $sql = "SELECT COUNT(*) as count FROM bookings b
                JOIN bookingDetails bd ON b.id = bd.bookingId
                JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hId AND b.status IN ('PENDING', 'CONFIRMED')";
        $result = $this->db->fetch($sql, [':hId' => $hotelId]);
        return $result['count'] > 0;
    }

    /**
     * 6. Tiện ích (Địa giới hành chính)
     */
    public function getCities() {
        return $this->db->fetchAll("SELECT * FROM cities ORDER BY name ASC");
    }

    public function getWardsByCity($cityId) {
        return $this->db->fetchAll("SELECT * FROM wards WHERE cityId = :cId", [':cId' => $cityId]);
    }

    /**
     * 7. Dashboard chi tiết cho từng khách sạn
     */
    public function getPhysicalRoomsStatus($hotelId) {
        $sql = "SELECT pr.*, rc.basePrice as price, rt.name as typeName
                FROM physicalRooms pr
                JOIN roomConfigurations rc ON pr.roomConfigId = rc.id
                JOIN roomTypes rt ON rc.roomTypeId = rt.id
                WHERE rc.hotelId = :hotelId AND pr.deleted_at IS NULL";
        return $this->db->fetchAll($sql, [':hotelId' => $hotelId]);
    }

    public function getOccupancyData($hotelId) {
        $sql = "SELECT 
                SUM(CASE WHEN pr.status = 'OCCUPIED' THEN 1 ELSE 0 END) as occupied_count,
                COUNT(pr.id) as total_count
                FROM physicalRooms pr
                JOIN roomConfigurations rc ON pr.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId AND pr.deleted_at IS NULL";
        return $this->db->fetch($sql, [':hotelId' => $hotelId]);
    }

    public function getTodayArrivals($hotelId) {
        $sql = "SELECT COUNT(*) as total FROM bookingDetails bd
                JOIN roomConfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId AND DATE(bd.checkIn) = CURRENT_DATE()";
        $result = $this->db->fetch($sql, [':hotelId' => $hotelId]);
        return $result['total'] ?? 0;
    }

}