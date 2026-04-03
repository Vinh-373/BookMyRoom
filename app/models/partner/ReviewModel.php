<?php
// require_once __DIR__ . '/../core/Model.php';

class ReviewModel extends Model {

    public function getReviewsByHotel($hotelId, $filters = []) {
        $sql = "SELECT r.*, u.fullName, u.avatarUrl, rt.name as roomTypeName, bd.checkIn 
                FROM reviews r
                JOIN users u ON r.userId = u.id
                JOIN bookingdetails bd ON r.bookingDetailId = bd.id
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                JOIN roomtypes rt ON rc.roomTypeId = rt.id
                WHERE rc.hotelId = :hId";
        
        $params = [':hId' => $hotelId];
        if (isset($filters['status'])) {
            if ($filters['status'] === 'pending') {
                $sql .= " AND (r.replyContent IS NULL OR r.replyContent = '')";
            } elseif ($filters['status'] === 'responded') {
                $sql .= " AND (r.replyContent IS NOT NULL AND r.replyContent != '')";
            }
        }
        
        $sql .= " ORDER BY r.createdAt DESC";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Lấy điểm đánh giá trung bình và tổng số lượng đánh giá
     */
    public function getAverageRating($hotelId) {
        $sql = "SELECT IFNULL(AVG(r.rating), 0) as avgRating, COUNT(r.id) as totalReviews 
                FROM reviews r
                JOIN bookingdetails bd ON r.bookingDetailId = bd.id
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hId";

        return $this->db->fetch($sql, [':hId' => $hotelId]);
    }

    /**
     * Cập nhật hoặc thêm mới phản hồi của Partner cho khách hàng
     */
    public function updateReply($reviewId, $replyText) {
        $sql = "UPDATE reviews 
                SET replyContent = :reply, 
                    replyDate = NOW() 
                WHERE id = :id";
                
        return $this->db->query($sql, [
            ':reply' => $replyText,
            ':id'    => $reviewId
        ]);
    }

    /**
     * Lấy thống kê số lượng đánh giá cho từng mức sao (1-5) để làm biểu đồ Breakdown
     */
    public function getRatingCountByStar($hotelId) {
        $sql = "SELECT r.rating, COUNT(*) as count
                FROM reviews r
                JOIN bookingdetails bd ON r.bookingDetailId = bd.id
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hId
                GROUP BY r.rating";
        
        return $this->db->fetchAll($sql, [':hId' => $hotelId]);
    }
}