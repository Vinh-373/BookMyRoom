<?php
require_once __DIR__ . '/../core/Model.php';
class InventoryModel extends Model {
    // Lấy giá tùy chỉnh
    public function getPricesInRange($hotelId, $start, $end) {
        $sql = "SELECT rp.* FROM roomprices rp 
                JOIN roomconfigurations rc ON rp.roomConfigId = rc.id 
                WHERE rc.hotelId = :hId AND rp.date BETWEEN :s AND :e";
        return $this->db->fetchAll($sql, [':hId' => $hotelId, ':s' => $start, ':e' => $end]);
    }

    /**
     * Quan trọng: Đếm số lượng phòng đã được đặt trong khoảng ngày
     * Trạng thái: Confirmed hoặc Completed
     */
    public function getBookedCount($hotelId, $start, $end) {
        $sql = "SELECT bd.roomConfigId, bd.checkIn, bd.checkOut, bd.quantity 
                FROM bookingdetails bd
                JOIN bookings b ON bd.bookingId = b.id 
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hId 
                AND b.status IN ('CONFIRMED', 'COMPLETED')
                AND NOT (bd.checkOut <= :s OR bd.checkIn >= :e)";
                
        return $this->db->fetchAll($sql, [
            ':hId' => $hotelId, 
            ':s' => $start, 
            ':e' => $end
        ]);
    }

    public function getManualInventory($hotelId, $start, $end) {
        $sql = "SELECT ri.roomConfigId, ri.date, ri.availableCount 
                FROM roominventory ri
                JOIN roomconfigurations rc ON ri.roomConfigId = rc.id
                WHERE rc.hotelId = :hId 
                AND ri.date BETWEEN :s AND :e";
                
        return $this->db->fetchAll($sql, [
            ':hId' => $hotelId, 
            ':s' => $start, 
            ':e' => $end
        ]);
    }

    public function updateDailyPrice($configId, $date, $price) {
        $sql = "INSERT INTO roomprices (roomConfigId, date, price) 
                VALUES (:id, :d, :p) 
                ON DUPLICATE KEY UPDATE price = :p";
        return $this->db->query($sql, [':id' => $configId, ':d' => $date, ':p' => $price]);
    }

    public function setManualClose($configId, $date) {
        $sql = "INSERT INTO roominventory (roomConfigId, date, availableCount) 
                VALUES (:id, :d, 0) 
                ON DUPLICATE KEY UPDATE availableCount = 0";
        return $this->db->query($sql, [':id' => $configId, ':d' => $date]);
    }

    public function removeManualOverride($configId, $date) {
        $sql = "DELETE FROM roominventory WHERE roomConfigId = :id AND date = :d";
        return $this->db->query($sql, [':id' => $configId, ':d' => $date]);
    }
}