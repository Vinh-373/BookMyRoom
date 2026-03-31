<?php
require_once __DIR__ . '/../core/Model.php';

class RoomModel extends Model {

    /**
     * Lấy danh sách các cấu hình phòng của khách sạn
     * Đồng bộ: Lấy basePrice, area, maxPeople từ bảng roomConfigurations
     */
    public function getRoomTypes($hotelId, $filters = []) {
        $params = [':hotelId' => $hotelId];
        $where = "WHERE rc.hotelId = :hotelId AND rc.deleted_at is null ";

        // Lọc theo loại phòng nếu có truyền vào
        if (!empty($filters['roomTypeId'])) {
            $where .= " AND rt.id = :roomTypeId ";
            $params[':roomTypeId'] = $filters['roomTypeId'];
        }

        $sql = "SELECT 
                    rc.id, 
                    rt.name, 
                    rc.basePrice, 
                    rc.area, 
                    rc.maxPeople,
                    (SELECT COUNT(*) FROM physicalRooms pr WHERE pr.roomConfigId = rc.id AND pr.deleted_at is null) as totalInventory
                FROM roomConfigurations rc
                INNER JOIN roomTypes rt ON rc.roomTypeId = rt.id
                $where
                ORDER BY rc.id DESC";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Lấy thống kê Inventory Health
     * Đồng bộ: Đếm trên bảng physicalRooms và lọc trạng thái bảo trì
     */
    public function getInventoryStats($hotelId) {
        $sql = "SELECT 
                    COUNT(pr.id) as totalActiveUnits,
                    SUM(CASE WHEN pr.status = 'MAINTENANCE' THEN 1 ELSE 0 END) as underMaintenance
                FROM physicalRooms pr
                INNER JOIN roomConfigurations rc ON pr.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId";
        
        return $this->db->fetch($sql, [':hotelId' => $hotelId]);
    }

    /**
     * Cập nhật thông tin cấu hình phòng
     * Đồng bộ: Cập nhật vào bảng roomConfigurations
     */
    public function updateRoomType($id, $data) {
        // Lưu ý: SQL của bạn không có cột 'description' trong roomConfigurations
        // Nếu muốn đổi tên loại phòng, phải update bảng roomTypes riêng. 
        // Ở đây tập trung update các thông số định nghĩa trong roomConfigurations.
        
        $sql = "UPDATE roomConfigurations SET 
                    basePrice = :basePrice, 
                    maxPeople = :maxPeople, 
                    area = :area
                WHERE id = :id";
        
        $params = [
            ':id'        => $id,
            ':basePrice' => $data['basePrice'],
            ':maxPeople' => $data['maxPeople'],
            ':area'      => $data['area']
        ];

        return $this->db->query($sql, $params);
    }

    /**
     * Xóa cấu hình phòng
     */
    public function deleteRoomType($id) {
        $sql = "UPDATE physicalrooms SET deleted_at = NOW() WHERE roomConfigId = :id;
         UPDATE roomConfigurations SET deleted_at = NOW() WHERE id = :id";
        return $this->db->query($sql, [':id' => $id]);
    }

    public function getUniqueRoomTypes($hotelId) {
        $sql = "SELECT DISTINCT rt.id, rt.name 
                FROM roomTypes rt
                INNER JOIN roomConfigurations rc ON rt.id = rc.roomTypeId
                WHERE rc.hotelId = :hotelId and rc.deleted_at is null";
        return $this->db->fetchAll($sql, [':hotelId' => $hotelId]);
    }

    public function createRoomConfiguration($data) {
        $sql = "INSERT INTO roomConfigurations (hotelId, roomTypeId, basePrice, area, maxPeople, createdAt) 
                VALUES (:hotelId, :roomTypeId, :basePrice, :area, :maxPeople, NOW())";
        
        return $this->db->query($sql, [
            ':hotelId'    => $data['hotelId'],
            ':roomTypeId' => $data['roomTypeId'],
            ':basePrice'  => $data['basePrice'],
            ':area'       => $data['area'],
            ':maxPeople'  => $data['maxPeople']
        ]);
    }

    public function getAllSystemRoomTypes() {
        $sql = "SELECT * FROM roomTypes ORDER BY name ASC";
        return $this->db->fetchAll($sql);
    }

    public function getPhysicalRooms($configId) {
        $sql = "SELECT * FROM physicalRooms WHERE roomConfigId = :configId AND deleted_at is null ORDER BY roomNumber ASC";
        return $this->db->fetchAll($sql, [':configId' => $configId]);
    }

    public function addPhysicalRoom($data) {
        $sql = "INSERT INTO physicalRooms (roomConfigId, roomNumber, floor, status) 
                VALUES (:configId, :number, :floor, 'AVAILABLE')";
        return $this->db->query($sql, $data);
    }

    public function countPhysicalRooms($roomConfigId) {
        $sql = "SELECT COUNT(*) as total FROM physicalrooms WHERE roomConfigId = :id AND deleted_at is null";
        $result = $this->db->fetch($sql, [':id' => $roomConfigId]);
        return $result ? (int)$result['total'] : 0;
    }

    public function getAllPhysicalRoomsByHotel($hotelId) {
        $sql = "SELECT pr.* FROM physicalRooms pr
                INNER JOIN roomConfigurations rc ON pr.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId AND pr.deleted_at is null AND rc.deleted_at is null";
        return $this->db->fetchAll($sql, [':hotelId' => $hotelId]);
    }

    public function checkRoomNumberExistsInHotel($hotelId, $roomNumber) {
        $sql = "SELECT COUNT(*) as count 
                FROM physicalRooms pr
                INNER JOIN roomConfigurations rc ON pr.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId AND pr.roomNumber = :roomNumber AND pr.deleted_at is null AND rc.deleted_at is null";
                
        $result = $this->db->fetch($sql, [
            ':hotelId'    => $hotelId,
            ':roomNumber' => $roomNumber
        ]);
        return $result['count'] > 0;
    }

    public function deleteRoomUnit($unitId) {
        $sql = "UPDATE physicalRooms SET deleted_at = NOW() WHERE id = :unitId ";
        return $this->db->fetchAll($sql, [':unitId' => $unitId]);
    }
}