<?php
require_once __DIR__ . '/../core/Model.php';

class InventoryModel extends Model {
    // Lấy giá tùy chỉnh trong khoảng ngày
    public function getPricesInRange($hotelId, $start, $end) {
        $sql = "SELECT rp.roomConfigId, rp.date, rp.price 
                FROM roomprices rp
                JOIN roomconfigurations rc ON rp.roomConfigId = rc.id
                WHERE rc.hotelId = :hId AND rp.date BETWEEN :s AND :e";
        return $this->db->fetchAll($sql, [':hId' => $hotelId, ':s' => $start, ':e' => $end]);
    }

    // Lấy dữ liệu đóng/mở phòng thủ công
    public function getManualInventory($hotelId, $start, $end) {
        $sql = "SELECT ri.roomConfigId, ri.date, ri.availableCount 
                FROM roominventory ri
                JOIN roomconfigurations rc ON ri.roomConfigId = rc.id
                WHERE rc.hotelId = :hId AND ri.date BETWEEN :s AND :e";
        return $this->db->fetchAll($sql, [':hId' => $hotelId, ':s' => $start, ':e' => $end]);
    }

    // Đếm số lượng phòng đã được đặt thực tế (Confirmed hoặc Completed)
    public function getBookedCount($hotelId, $start, $end) {
        $sql = "SELECT bd.roomConfigId, bd.checkIn, bd.checkOut, bd.quantity 
                FROM bookingdetails bd
                JOIN bookings b ON bd.bookingId = b.id 
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hId AND b.status IN ('CONFIRMED', 'COMPLETED')
                AND NOT (bd.checkOut <= :s OR bd.checkIn >= :e)";
        return $this->db->fetchAll($sql, [':hId' => $hotelId, ':s' => $start, ':e' => $end]);
    }

    // Cập nhật lẻ (Single Upsert)
    public function updateDailyPrice($configId, $date, $price) {
        $sql = "INSERT INTO roomprices (roomConfigId, date, price) VALUES (:id, :d, :p) 
                ON DUPLICATE KEY UPDATE price = VALUES(price)";
        return $this->db->query($sql, [':id' => $configId, ':d' => $date, ':p' => $price]);
    }

    public function setManualClose($configId, $date) {
        $sql = "INSERT INTO roominventory (roomConfigId, date, availableCount) VALUES (:id, :d, 0) 
                ON DUPLICATE KEY UPDATE availableCount = 0";
        return $this->db->query($sql, [':id' => $configId, ':d' => $date]);
    }

    public function removeManualOverride($configId, $date) {
        $sql = "DELETE FROM roominventory WHERE roomConfigId = :id AND date = :d";
        return $this->db->query($sql, [':id' => $configId, ':d' => $date]);
    }

    // XỬ LÝ HÀNG LOẠT (BATCH PROCESSING)
    public function bulkUpdatePrices($configIds, $dates, $price) {
        $values = []; $placeholders = [];
        foreach ($configIds as $cId) {
            foreach ($dates as $d) {
                $placeholders[] = "(?, ?, ?)";
                array_push($values, $cId, $d, $price);
            }
        }
        $sql = "INSERT INTO roomprices (roomConfigId, date, price) VALUES " . implode(',', $placeholders) . " 
                ON DUPLICATE KEY UPDATE price = VALUES(price)";
        return $this->db->query($sql, $values);
    }

    public function bulkSetClose($configIds, $dates) {
        $values = []; $placeholders = [];
        foreach ($configIds as $cId) {
            foreach ($dates as $d) {
                $placeholders[] = "(?, ?, 0)";
                array_push($values, $cId, $d);
            }
        }
        $sql = "INSERT INTO roominventory (roomConfigId, date, availableCount) VALUES " . implode(',', $placeholders) . " 
                ON DUPLICATE KEY UPDATE availableCount = 0";
        return $this->db->query($sql, $values);
    }

    public function bulkRemoveOverride($configIds, $dates) {
        $configP = implode(',', array_fill(0, count($configIds), '?'));
        $dateP = implode(',', array_fill(0, count($dates), '?'));
        $sql = "DELETE FROM roominventory WHERE roomConfigId IN ($configP) AND date IN ($dateP)";
        return $this->db->query($sql, array_merge($configIds, $dates));
    }
}