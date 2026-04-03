<?php
// require_once __DIR__ . '/../core/Model.php';

class FinanceModel extends Model {
    /**
     * Lấy tổng doanh thu dựa trên trạng thái 'COMPLETED'
     */
    public function getTotalRevenueByPeriod($hotelId, $period) {
        $where = $this->buildPeriodCondition($period);
        $sql = "SELECT SUM(b.totalAmount) as total 
                FROM bookings b
                JOIN bookingdetails bd ON b.id = bd.bookingId
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                $where AND rc.hotelId = :hotelId AND b.status = 'COMPLETED'";
                
        $result = $this->db->fetch($sql, [':hotelId' => $hotelId]);
        return $result['total'] ?? 0;
    }

    /**
     * Lấy doanh thu phân loại theo tên loại phòng (Standard, Deluxe...)
     */
    public function getRevenueByRoomType($hotelId, $period) {
        $where = $this->buildPeriodCondition($period);
        $sql = "SELECT rt.name as room_type, SUM(bd.amount) as amount 
                FROM bookings b
                JOIN bookingdetails bd ON b.id = bd.bookingId
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                JOIN roomtypes rt ON rc.roomTypeId = rt.id
                $where AND rc.hotelId = :hotelId AND b.status = 'COMPLETED'
                GROUP BY rt.id";
                
        return $this->db->fetchAll($sql, [':hotelId' => $hotelId]);
    }

    /**
     * Lấy lịch sử giao dịch từ bảng payments
     */
    public function getRecentPayouts($hotelId, $limit = 5) {
        $sql = "SELECT p.id as transaction_id, p.amount, p.paymentStatus as status, p.createdAt as created_at
                FROM payments p
                JOIN bookings b ON p.bookingId = b.id
                JOIN bookingdetails bd ON b.id = bd.bookingId
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId
                ORDER BY p.createdAt DESC LIMIT $limit";
                
        return $this->db->fetchAll($sql, [':hotelId' => $hotelId]);
    }

    private function buildPeriodCondition($period) {
        switch ($period) {
            case 'last_month': 
                return " WHERE MONTH(b.createdAt) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(b.createdAt) = YEAR(CURRENT_DATE()) ";
            case 'this_year':  
                return " WHERE YEAR(b.createdAt) = YEAR(CURRENT_DATE()) ";
            default:           
                return " WHERE MONTH(b.createdAt) = MONTH(CURRENT_DATE()) AND YEAR(b.createdAt) = YEAR(CURRENT_DATE()) ";
        }
    }

    public function getAllTransactions($hotelId, $filters, $limit, $offset) {
        $params = [':hotelId' => $hotelId];
        $where = " WHERE rc.hotelId = :hotelId ";

        // Lọc theo trạng thái thanh toán (PAID, PENDING,...)
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $where .= " AND p.paymentStatus = :status ";
            $params[':status'] = $filters['status'];
        }

        // Lọc theo phương thức thanh toán
        if (!empty($filters['method']) && $filters['method'] !== 'all') {
            $where .= " AND p.paymentMethod = :method ";
            $params[':method'] = $filters['method'];
        }

        $sql = "SELECT p.*, b.totalAmount as bookingTotal, u.fullName as guestName
                FROM payments p
                JOIN bookings b ON p.bookingId = b.id
                JOIN users u ON b.userId = u.id
                JOIN bookingdetails bd ON b.id = bd.bookingId
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                $where
                ORDER BY p.createdAt DESC 
                LIMIT $limit OFFSET $offset";

        return $this->db->fetchAll($sql, $params);
    }

    public function getTransactionCount($hotelId, $filters) {
        $params = [':hotelId' => $hotelId];
        $sql = "SELECT COUNT(p.id) as total 
                FROM payments p
                JOIN bookings b ON p.bookingId = b.id
                JOIN bookingdetails bd ON b.id = bd.bookingId
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hotelId";
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'] ?? 0;
    }
}